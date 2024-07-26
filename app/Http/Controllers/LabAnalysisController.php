<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\LabAnalysis;

class LabAnalysisController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'point'=> 'required|numeric',
            'cost' => 'required|numeric',
        ]);
    }

    public function getLabAnalysis()
    {
        if (!in_array(Auth::user()->role_id, [1, 2, 3, 5])) {
            return response()->json(['message' => 'Access Denied, You Cannot get LabAnalysis'], 403);
        }

        $labAnalyses = LabAnalysis::with('analysis', 'laboratory')->get();
        $response = $labAnalyses->map(function ($labAnalysis) {
            $location = app('App\Http\Controllers\LocationController')->getLocationById($labAnalysis->laboratory_id);
            return [
                'id' => $labAnalysis->id,
                'analysis_id' => $labAnalysis->analysis_id,
                'laboratory_id' => $labAnalysis->laboratory_id,
                'cost' => $labAnalysis->cost,
                'point' => $labAnalysis->point,
                'created_at' => $labAnalysis->created_at,
                'updated_at' => $labAnalysis->updated_at,
                'analysis' => $labAnalysis->analysis,
                'laboratory' => $labAnalysis->laboratory,
                'location' => $location->original
            ];
        });

        return response()->json($response, 200);
    }

    // Analysis For specific Lab
    public function getAnalysis($laboratory_id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message' => 'Access Denied, You Cannot get LabAnalysis'], 403);
        }

        $labAnalyses = LabAnalysis::where('laboratory_id', $laboratory_id)->with('analysis', 'laboratory')->get();
        $response = $labAnalyses->map(function ($labAnalysis) {
            $location = app('App\Http\Controllers\LocationController')->getLocationById($labAnalysis->laboratory_id);
            return [
                'id' => $labAnalysis->id,
                'analysis_id' => $labAnalysis->analysis_id,
                'laboratory_id' => $labAnalysis->laboratory_id,
                'cost' => $labAnalysis->cost,
                'point' => $labAnalysis->point,
                'created_at' => $labAnalysis->created_at,
                'updated_at' => $labAnalysis->updated_at,
                'analysis' => $labAnalysis->analysis,
                'laboratory' => $labAnalysis->laboratory,
                'location' => $location->original
            ];
        });

        return response()->json($response, 200);
    }

   
    public function addLabAnalysis(Request $request)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message' => 'Access Denied, You Cannot Add LabAnalysis'], 403);
        }

        $validatedData = $this->validator($request->all());
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 400);
        }

        $labAnalysis = new LabAnalysis;
        $labAnalysis->laboratory_id = Auth::user()->laboratory_id;
        $labAnalysis->analysis_id = $request['analysis_id']??1;
        $labAnalysis->point = $request['point'];
        $labAnalysis->cost = $request['cost'];

        $labAnalysis->save();

        return response()->json($labAnalysis, 201);
    }

   
    public function updateLabAnalysis(Request $request, $id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message' => 'Access Denied, You Cannot update LabAnalysis'], 403);
        }

        $labAnalysis = LabAnalysis::find($id);
        if (!$labAnalysis) {
            return response()->json(['message' => 'LabAnalysis not found'], 404);
        }

if ($request['point']) {
            $labAnalysis->point = $request['point'];
        }

        if ($request['cost']) {
            $labAnalysis->cost = $request['cost'];
        }

        $labAnalysis->save();

        return response()->json($labAnalysis, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteLabAnalysis($id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message' => 'Access Denied, You Cannot delete LabAnalysis'], 403);
        }

        $labAnalysis = LabAnalysis::find($id);
        if (!$labAnalysis) {
            return response()->json(['message' => 'LabAnalysis not found'], 404);
        }

        $labAnalysis->delete();

        return response()->json(['message' => 'Deleted Successful'], 200);
    }

    // Search by analysis name
    public function searchlabAnalysesName(Request $request)
    {
        $labAnalyses = LabAnalysis::whereHas('analysis', function($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request['query'] . '%');
        })
        ->with('analysis')
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($labAnalyses, 200);
    }

    // Search by analysis price
    public function searchlabAnalysesprice(Request $request)
    {
        $labAnalyses = LabAnalysis::whereHas('analysis', function($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request['query'] . '%');
        })
        ->with('analysis')
        ->orderBy('cost', 'asc')
        ->get();

        return response()->json($labAnalyses, 200);
    }
}