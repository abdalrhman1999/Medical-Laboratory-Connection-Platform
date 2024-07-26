<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Laboratory;
use App\Models\Location;
class LaboratoryController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'lab_name' => 'required|string|max:15',
            'lab_information' => 'required|string',
            'lab_number'=> 'required|numeric',
            ' score' =>  'numeric',

        ]);
    }
    public function addLaboratory(Request $request)
    {
        if (Auth::user()->role_id !=2 && Auth::user()->role_id !=1 ){
            return response()->json(['message'=>'Access Denied, You Can Not Add laboratory'], 403);
        }

        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }
        $user = Auth::user();

        $Laboratory = new Laboratory;

        $Laboratory->lab_name = $request['lab_name'];
        $Laboratory->lab_information = $request['lab_information'];
        $Laboratory->lab_number = $request['lab_number'];
        // $Laboratory->score = $request['score'];

        $Laboratory->save();
        $location = new Location;
        $location->user_id = $user->id;
        $location->laboratory_id = $Laboratory->id;
        $location->latitude = $request->lat;
        $location->longitude = $request->lang;

        $location->save();

        return response()->json($Laboratory, 201);
    }


    public function updateLaboratory(Request $request, $id)
    {
        if (Auth::user()->role_id !=2 ){
            return response()->json(['message'=>'Access Denied, You Can Not update Laboratory'], 403);
        }

        $Laboratory = Laboratory::find($id);

        if ($request['lab_name'])
            $Laboratory->lab_name = $request['lab_name'];

        if ($request['lab_information'])
            $Laboratory->lab_information = $request['lab_information'];

        if ($request['lab_number'])
            $Laboratory->lab_number = $request['lab_number'];

        // if ($request['score'])
        //     $Laboratory->score = $request['score'];


        $Laboratory->save();

        return response()->json($Laboratory, 201);
    }


    public function getLaboratory()
    {
        if ( Auth::user()->role_id !=1 && Auth::user()->role_id !=2 ){
            return response()->json(['message'=>'Access Denied, You Can Not get Laboratory'], 403);
        }
        $Laboratory = DB::table('laboratories')
            ->join('locations', 'laboratories.id', '=', 'locations.laboratory_id')
            ->get();
        ;
        return response()->json($Laboratory, 200);
    }
    public function deleteLaboratory($id)
    {
        if (Auth::user()->role_id !=2 ){
            return response()->json(['message'=>'Access Denied, You Can Not delete Laboratory'], 403);
        }
        $Laboratory = Laboratory::find($id);

        $Laboratory->delete();

        return response()->json(['Deleted Successful'], 200);
    }

    //search

    public function searchLaboratory(Request $request)

    {
        $Laboratory = Laboratory::where('lab_name', 'LIKE', '%' . $request['query'] . '%')
                        ->orderBy('created_at', 'desc')->get();

        return response()->json($Laboratory, 200);
    }







}
