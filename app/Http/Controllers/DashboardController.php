<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\SystemLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $studentsCount = Student::count();
        $subjectsCount = Subject::count();
        $majorsCount = Major::count();
        
        $activeYear = AcademicYear::where('status', 'active')->first() 
            ?? AcademicYear::orderBy('year', 'desc')->first();
            
        $currentYearStr = $activeYear ? $activeYear->year : 'N/A';

        // Distribution by majors
        $majorDistribution = Major::withCount('students as student_count')->get();

        // Recent System Logs (limit to 5)
        $recentLogs = SystemLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Major list for details table
        $majorsList = Major::withCount('students as student_count')->get();

        return view('dashboard', compact(
            'studentsCount',
            'subjectsCount',
            'majorsCount',
            'currentYearStr',
            'majorDistribution',
            'recentLogs',
            'majorsList'
        ));
    }
}
