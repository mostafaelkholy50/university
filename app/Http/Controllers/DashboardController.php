<?php

namespace App\Http\Controllers;

use App\Models\enroll;
use App\Models\grades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function Count(){
        $userCount = DB::table('users')->count();
        $doctorCount = DB::table('doctors')->count();
        $courseCount = DB::table('courses')->count();
        $enrollmentCount = enroll::where('payment_status', 'paid')->count();
        return response()->json([
            'status' => 200,
            'message' => 'Counts fetched successfully.',
            'data' => [
                'user_count' => $userCount,
                'doctor_count' => $doctorCount,
                'course_count' => $courseCount,
                'enrollment_count' => $enrollmentCount,
            ]
        ]);
    }
    public function successRateBySpecialty()
{
    // لو عاوز تحدد مجموعة تخصصات معينة، حطّهم هنا
    $specialties = [
        'Computer Science',
        'Information Technology',
        'Engineering',
        'Mechatronics',
    ];

    $rates = grades::join('users', 'grades.user_id', '=', 'users.id')
        ->whereIn('users.specialty', $specialties)   // بدل section
        ->groupBy('users.specialty')                  // بدل section
        ->select([
            'users.specialty',
            DB::raw('COUNT(grades.id) AS total_records'),
            DB::raw("SUM(CASE WHEN grades.grade != 'Fail' THEN 1 ELSE 0 END) AS passed_records"),
            DB::raw("ROUND(
                SUM(CASE WHEN grades.grade != 'Fail' THEN 1 ELSE 0 END)
                /
                COUNT(grades.id)
                * 100
            , 2) AS pass_percentage".'%')
        ])
        ->get();

    return response()->json([
        'status' => 200,
        'message' => 'Success rates fetched successfully.',
        'data' => $rates
    ]);
}
}
