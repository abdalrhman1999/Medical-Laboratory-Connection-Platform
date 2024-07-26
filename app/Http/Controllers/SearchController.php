<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;
use App\Models\Analysis;
use App\Models\LabAnalysis;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = 10; // نطاق البحث بالكيلومترات

        // البحث عن المخابر القريبة باستخدام Haversine formula
        $laboratories = Laboratory::select('*')
            ->selectRaw('( 6371 * acos( cos( radians(?) ) *
               cos( radians( latitude ) )
               * cos( radians( longitude ) - radians(?)
               ) + sin( radians(?) ) *
               sin( radians( latitude ) ) )
             ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius);

        // البحث حسب اسم التحليل إذا كان متوفراً
        if ($request->filled('name')) {
            $laboratories->whereHas('labAnalyses.analysis', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        $laboratories = $laboratories->orderBy('distance')->get();

        // جلب نتائج التحليل المتعلقة بالمخابر القريبة
        $labAnalysisQuery = LabAnalysis::whereIn('laboratory_id', $laboratories->pluck('id'));

        // البحث حسب السعر إذا كان متوفراً
        if ($request->filled('cost')) {
            $labAnalysisQuery->where('cost', '<=', $request->input('cost'));
        }

        $results = $labAnalysisQuery->get();

        return response()->json([
            'laboratories' => $laboratories,
            'results' => $results,
        ]);
    }
}


