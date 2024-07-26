<?php

namespace App\Http\Controllers;
use App\Models\Analysis;
use App\Models\Laboratory;
use App\Models\LabAnalysis;

use App\Models\UserAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class AnalysisController extends Controller {
    protected function validator( array $data ) {
        return Validator::make( $data, [
            'name' => 'required|string|max:15',
            'description' => 'required|string',
        ] );
    }

    public function getAnalysis() {
        if ( Auth::user()->role_id != 2  && Auth::user()->role_id != 3) {
            return response()->json( [ 'message'=>'Access Denied, You Can Not get Analysis' ], 403 );
        }
        $Analyses = Analysis::all();
        return response()->json( $Analyses, 200 );
    }

    /**
    * Store a newly created resource in storage.
    */
    public function addAnalysis( Request $request ) {
        if ( Auth::user()->role_id != 2 && Auth::user()->role_id != 3 ) {
            return response()->json( [ 'message'=>'Access Denied, You Can Not Add Analysis' ], 403 );
        }

        $validatedData = $this->validator( $request->all() );
        if ( $validatedData->fails() ) {
            return response()->json( [ 'errors'=>$validatedData->errors() ], 400 );
        }
        $user = Auth::user();

        $Analyses = new Analysis;

        $Analyses->name = $request[ 'name' ];
        $Analyses->description = $request[ 'description' ];

        $Analyses->save();

        return response()->json( $Analyses, 201 );
    }
  
    public function updateAnalysis( Request $request, $id ) {
        if ( Auth::user()->role_id != 2 ) {
            return response()->json( [ 'message'=>'Access Denied, You Can Not update Analysis' ], 403 );
        }

        $Analysis = Analysis::find( $id );

        if ( $request[ 'name' ] )
        $Analysis->name = $request[ 'name' ];

        if ( $request[ 'description' ] )
        $Analysis->description = $request[ 'description' ];

        $Analysis->save();

        return response()->json( $Analysis, 201 );
    }

 

    public function deleteAnalysis( $id ) {
        if ( Auth::user()->role_id != 2 ) {
            return response()->json( [ 'message'=>'Access Denied, You Can Not delete Analysis' ], 403 );
        }
        $Analysis = Analysis::find( $id );

        $Analysis->delete();

        return response()->json( [ 'Deleted Successful' ], 200 );
    }

    //search

    public function searchAnalysisName( Request $request ) {
        $analysis = Analysis::where( 'name', 'LIKE', '%' . $request[ 'query' ] . '%' )
        ->orderBy( 'created_at', 'desc' )->get();

        return response()->json( $analysis, 200 );
    }

}