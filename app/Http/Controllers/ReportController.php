<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function printInvoiceReport(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Invoice::with('student.major');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('student_id', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        if ($status) {
            $query->where('payment_status', $status);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary data for the report
        $summary = [
            'total_count' => $invoices->count(),
            'total_amount' => $invoices->sum('total_amount'),
            
            'paid_count' => $invoices->where('payment_status', 'paid')->count(),
            'paid_amount' => $invoices->where('payment_status', 'paid')->sum('total_amount'),
            
            'unpaid_count' => $invoices->where('payment_status', 'unpaid')->count(),
            'unpaid_amount' => $invoices->where('payment_status', 'unpaid')->sum('total_amount'),
            
            'cancelled_count' => $invoices->where('payment_status', 'cancelled')->count(),
            'cancelled_amount' => $invoices->where('payment_status', 'cancelled')->sum('total_amount'),
        ];

        // School details
        $schoolName = setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້');
        $schoolAddress = setting('school_address', 'ບ້ານວັດຈັນ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ');
        $schoolPhone = setting('school_phone', '021 215 038');
        $schoolEmail = setting('school_email', 'info@ongtue.edu.la');

        // Prepare Logo base64
        $logoSetting = setting('school_logo');
        $logoPath = $logoSetting ? storage_path('app/public/' . $logoSetting) : public_path('logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $ext = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'invoices' => $invoices,
            'summary' => $summary,
            'logoBase64' => $logoBase64,
            'schoolName' => $schoolName,
            'schoolAddress' => $schoolAddress,
            'schoolPhone' => $schoolPhone,
            'schoolEmail' => $schoolEmail,
            'search' => $search,
            'status' => $status,
            'printDate' => $this->formatLaoDate(now()),
        ];

        $pdf = Pdf::loadView('pdf.invoice_report', $data);
        return $pdf->stream('Invoice-Report-' . date('Ymd-His') . '.pdf');
    }

    public function printInvoice($id)
    {
        $invoice = Invoice::with('student.major')->findOrFail($id);
        
        // Prepare QR Code base64 — prefer uploaded bank QR from settings
        $qrBase64 = null;
        $bankQrSetting = setting('bank_qr_code');
        if ($bankQrSetting) {
            $bankQrPath = storage_path('app/public/' . $bankQrSetting);
            if (file_exists($bankQrPath)) {
                $ext = pathinfo($bankQrPath, PATHINFO_EXTENSION);
                $qrBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($bankQrPath));
            }
        }
        // Fallback to auto-generated QR per-invoice
        if (!$qrBase64 && $invoice->qr_code_path) {
            $qrPath = storage_path('app/public/qrcodes/' . $invoice->qr_code_path);
            if (file_exists($qrPath)) {
                $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrPath));
            }
        }

        // School details
        $schoolName = setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້');
        $schoolAddress = setting('school_address', 'ບ້ານວັດຈັນ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ');
        $schoolPhone = setting('school_phone', '021 215 038');
        $schoolEmail = setting('school_email', 'info@ongtue.edu.la');

        // Prepare Logo base64
        $logoSetting = setting('school_logo');
        $logoPath = $logoSetting ? storage_path('app/public/' . $logoSetting) : public_path('logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $ext = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'invoice' => $invoice,
            'qrBase64' => $qrBase64,
            'logoBase64' => $logoBase64,
            'schoolName' => $schoolName,
            'schoolAddress' => $schoolAddress,
            'schoolPhone' => $schoolPhone,
            'schoolEmail' => $schoolEmail,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->stream('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function printTranscript($studentId)
    {
        $student = Student::with(['major', 'academicYear'])->findOrFail($studentId);

        // Fetch all grades with academic year info
        $grades = Grade::where('student_id', $studentId)
            ->whereIn('status', ['completed', 'failed', 'incomplete'])
            ->with(['subject', 'academicYear'])
            ->orderBy('academic_year_id', 'asc')
            ->orderBy('semester', 'asc')
            ->get();

        // Group by academic_year + semester (key = "year|semester")
        $groupedGrades = $grades->groupBy(function ($g) {
            $yearName = $g->academicYear->year ?? 'ບໍ່ລະບຸ';
            return $yearName . '|' . $g->semester;
        });

        // Build semester blocks with summaries
        $semesterBlocks = [];
        $totalCreditsAttempted = 0;
        $totalCreditsEarned = 0;
        $totalPointsEarned = 0;

        foreach ($groupedGrades as $key => $semGrades) {
            [$yearName, $semNo] = explode('|', $key);

            $semCreditsAttempted = 0;
            $semCreditsEarned = 0;
            $semPointsEarned = 0;

            foreach ($semGrades as $g) {
                $subjCredits = $g->subject->credits ?? 0;
                $semCreditsAttempted += $subjCredits;
                $semPointsEarned += ($g->grade_point * $subjCredits);
                if ($g->grade_point > 0) {
                    $semCreditsEarned += $subjCredits;
                }
            }

            $semGpa = $semCreditsAttempted > 0 ? round($semPointsEarned / $semCreditsAttempted, 2) : 0.00;

            $semesterBlocks[] = [
                'year_name' => $yearName,
                'semester' => $semNo,
                'grades' => $semGrades->values(),
                'credits_attempted' => $semCreditsAttempted,
                'credits_earned' => $semCreditsEarned,
                'gpa' => $semGpa,
                'gpa_letter' => $this->gpaToLetter($semGpa),
            ];

            $totalCreditsAttempted += $semCreditsAttempted;
            $totalCreditsEarned += $semCreditsEarned;
            $totalPointsEarned += $semPointsEarned;
        }

        $cumulativeGpa = $totalCreditsAttempted > 0 ? round($totalPointsEarned / $totalCreditsAttempted, 2) : 0.00;

        // Prepare Logo base64
        $logoSetting = setting('school_logo');
        $logoPath = $logoSetting ? storage_path('app/public/' . $logoSetting) : public_path('logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $ext = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // School details
        $schoolName = setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້');

        // Document number: YYYY/NNN/ວກສ.ອກ.ພາ
        $docYear = date('Y');
        $docNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $documentNumber = "{$docYear}/{$docNumber}/ວກສ.ອກ.ພາ";

        // Academic year range for display
        $studentAcademicYear = $student->academicYear->year ?? '-';

        $data = [
            'student' => $student,
            'semesterBlocks' => $semesterBlocks,
            'totalCreditsAttempted' => $totalCreditsAttempted,
            'totalCreditsEarned' => $totalCreditsEarned,
            'cumulativeGpa' => $cumulativeGpa,
            'cumulativeGpaLetter' => $this->gpaToLetter($cumulativeGpa),
            'logoBase64' => $logoBase64,
            'schoolName' => $schoolName,
            'documentNumber' => $documentNumber,
            'printDate' => $this->formatLaoDate(now()),
        ];

        $pdf = Pdf::loadView('pdf.transcript', $data);
        return $pdf->stream('Transcript-' . ($student->student_id ?: $student->id) . '.pdf');
    }

    /**
     * Convert GPA to letter grade
     */
    private function gpaToLetter($gpa)
    {
        if ($gpa >= 3.50) return 'A';
        if ($gpa >= 3.00) return 'B+';
        if ($gpa >= 2.50) return 'B';
        if ($gpa >= 2.00) return 'C+';
        if ($gpa >= 1.50) return 'C';
        if ($gpa >= 1.00) return 'D';
        return 'F';
    }

    /**
     * Format a date in Lao style: "ວັນທີ DD ເດືອນ MM ປີ YYYY"
     */
    private function formatLaoDate($date)
    {
        $laoMonths = [
            1 => 'ມັງກອນ', 2 => 'ກຸມພາ', 3 => 'ມີນາ',
            4 => 'ເມສາ', 5 => 'ພຶດສະພາ', 6 => 'ມິຖຸນາ',
            7 => 'ກໍລະກົດ', 8 => 'ສິງຫາ', 9 => 'ກັນຍາ',
            10 => 'ຕຸລາ', 11 => 'ພະຈິກ', 12 => 'ທັນວາ',
        ];

        $day = $date->day;
        $month = $laoMonths[$date->month] ?? '';
        $year = $date->year;

        return "ວັນທີ {$day} {$month} {$year}";
    }
}
