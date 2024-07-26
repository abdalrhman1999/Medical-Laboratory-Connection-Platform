<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laboratory;
use App\Models\Analysis;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getAdminStatistics()
    {
        if (Auth::user()->role_id != 1) {
            return response()->json(['message' => 'Access Denied, You Cannot get admin statistics'], 403);
        }

        $num_staffs = User::where('role_id', 2)->count();
        $num_doctor_laboratories = User::where('role_id', 3)->count();
        $num_nurses = User::where('role_id', 4)->count();
        $num_users = User::where('role_id', 5)->count();

        $num_laboratories = Laboratory::count();
        $num_analyses = Analysis::count();

        $most_freq_analyses = DB::table('user_analyses')
            ->join('lab_analyses', 'lab_analyses.id', '=', 'user_analyses.lab_analysis_id')
            ->join('analyses', 'analyses.id', '=', 'lab_analyses.analysis_id')
            ->select('analysis_id', 'analyses.name', DB::raw('count(*) as freq'))
            ->groupBy('analysis_id', 'analyses.name')
            ->orderBy('freq')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => [
                'num_staffs' => $num_staffs,
                'num_doctor_laboratories' => $num_doctor_laboratories,
                'num_nurses' => $num_nurses,
                'num_users' => $num_users,
                'num_laboratories' => $num_laboratories,
                'num_analyses' => $num_analyses,
                'most_freq_analyses' => $most_freq_analyses,
            ]
        ]);
    }

    public function getStaffStatistics()
    {
        if (Auth::user()->role_id != 2) {
            return response()->json(['message' => 'Access Denied, You Cannot get staff statistics'], 403);
        }

        $num_doctor_laboratories = User::where('role_id', 3)->count();
        $num_nurses = User::where('role_id', 4)->count();
        $num_users = User::where('role_id', 5)->count();

        $num_laboratories = Laboratory::count();
        $num_analyses = Analysis::count();

        $most_freq_analyses = DB::table('user_analyses')
            ->join('lab_analyses', 'lab_analyses.id', '=', 'user_analyses.lab_analysis_id')
            ->join('analyses', 'analyses.id', '=', 'lab_analyses.analysis_id')
            ->select('analysis_id', 'name', DB::raw('count(*) as freq'))
            ->groupBy('analysis_id', 'name')
            ->orderBy('freq')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => [
                'num_doctor_laboratories' => $num_doctor_laboratories,
                'num_nurses' => $num_nurses,
                'num_users' => $num_users,
                'num_laboratories' => $num_laboratories,
                'num_analyses' => $num_analyses,
                'most_freq_analyses' => $most_freq_analyses,
            ]
        ]);
    }

    public function getLaboratoryStatistics()
    {
        if (Auth::user()->role_id !=3){
            return response()->json(['message'=>'Access Denied, You Can Not get admin statistics'], 403);
        }

        $num_nurses = count(User::where([['role_id', '=', 4], ['laboratory_id', '=', Auth::user()->laboratory_id]])->get());

        $num_analyses = count(LabAnalysis::where('laboratory_id', '=', Auth::user()->laboratory_id)->get());

        $most_freq_analyses = DB::table('user_analyses')
                ->join('lab_analyses', 'lab_analyses.id', '=', 'user_analyses.lab_analysis_id')
                ->join('analyses', 'analyses.id', '=', 'lab_analyses.analysis_id')
                ->select('analysis_id', 'name', DB::raw('count(*) as freq'))
                ->groupBy('analysis_id')
                ->having('laboratory_id','=', Auth::user()->laboratory_id)
                // ->where('laboratory_id','=', Auth::user()->laboratory_id)
                ->orderBy('freq')
                ->take(10)
                ->get();

        return response()->json(['data' => [
            'num_nurses' => $num_nurses,
            'num_analyses' => $num_analyses,
            'most_freq_analyses' => $most_freq_analyses,
        ]]);

}
}
