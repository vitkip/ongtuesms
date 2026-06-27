<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    /** Scan QR → auto-login and show dashboard */
    public function loginViaQr(string $token)
    {
        $student = Student::where('qr_token', $token)->firstOrFail();

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard');
    }

    /** Student dashboard — requires student guard */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();

        $grades = Grade::where('student_id', $student->id)
            ->whereIn('status', ['completed', 'failed', 'incomplete'])
            ->with(['subject', 'academicYear'])
            ->orderBy('academic_year_id', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Compute cumulative GPA
        $totalCredits = 0;
        $totalPoints  = 0;
        foreach ($grades as $g) {
            $credits       = $g->subject->credits ?? 0;
            $totalCredits += $credits;
            $totalPoints  += $g->grade_point * $credits;
        }
        $cumulativeGpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : null;

        $invoices = Invoice::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalOwed = $invoices->where('payment_status', 'unpaid')->sum('total_amount');
        $totalPaid = $invoices->where('payment_status', 'paid')->sum('total_amount');

        return view('student.dashboard', compact(
            'student', 'grades', 'cumulativeGpa', 'invoices', 'totalOwed', 'totalPaid'
        ));
    }

    /** Logout from student guard */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
