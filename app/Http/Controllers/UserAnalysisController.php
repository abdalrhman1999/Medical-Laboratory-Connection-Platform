<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\LabAnalysis;
use App\Models\UserAnalysis;

class UserAnalysisController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'result' => 'required|string',
            'min' => 'required|string',
            'max' => 'required|string',
        ]);
    }

    public function getUserAnalysis($user_id)
    {
        if (Auth::user()->role_id !=3 && Auth::user()->role_id !=5 ){
            return response()->json(['message'=>'Access Denied, You Can Not get userAnalysis'], 403);
        }

        $UserAnalysis = UserAnalysis::where('user_id', $user_id)
                                    ->with(['user', 'labanalysis.analysis', 'labanalysis.laboratory'])
                                    ->get();
        return response()->json($UserAnalysis, 200);
    }

    public function addUserAnalysis(Request $request)
    {
        if (Auth::user()->role_id !=3 ){
            return response()->json(['message'=>'Access Denied, You Can Not Add UserAnalysis'], 403);
        }

        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }

        $user = Auth::user();
        $UserAnalysis = new UserAnalysis;
        $UserAnalysis->user_id = $user['id'];
        $UserAnalysis->lab_analysis_id = $request['lab_analysis_id'];
        $UserAnalysis->result = $request['result'];
        $UserAnalysis->min = $request['min'];
        $UserAnalysis->max = $request['max'];

        $UserAnalysis->save();

        return response()->json($UserAnalysis, 201);
    }

    public function updateUserAnalysis(Request $request, $id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message' => 'Access Denied, You Cannot update UserAnalysis'], 403);
        }

        $userAnalysis = UserAnalysis::find($id);

        if (!$userAnalysis) {
            return response()->json(['message' => 'UserAnalysis not found'], 404);
        }

        if ($request->has('result')) {
            $userAnalysis->result = $request->result;
        }

        if ($request->has('min')) {
            $userAnalysis->min = $request->min;
        }

        if ($request->has('max')) {
            $userAnalysis->max = $request->max;
        }

        $userAnalysis->save();

        return response()->json($userAnalysis, 200);
    }

    public function deleteUserAnalysis($id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message' => 'Access Denied, You Cannot delete UserAnalysis'], 403);
        }

        $userAnalysis = UserAnalysis::find($id);

        if (!$userAnalysis) {
            return response()->json(['message' => 'UserAnalysis not found'], 404);
        }

        $userAnalysis->delete();

        return response()->json(['message' => 'UserAnalysis deleted successfully'], 200);
    }
}
