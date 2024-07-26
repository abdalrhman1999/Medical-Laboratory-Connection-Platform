<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Location;

class LocationController extends Controller
{
    protected function validator( array $data ) {
        return Validator::make( $data, [
            'user_id' => 'nullable|exists:users,id',
            'laboratory_id' => 'nullable',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ] );
    }

    public function getLocation() {

        $location = Location::with('user', 'laboratory')->get();
        return response()->json( $location, 200 );
    }


    public function getLocationById($labId) {

        $location = Location::where('laboratory_id', $labId)->first();
        return response()->json( $location, 200 );
    }
    public function addLocation( Request $request ) {

        $validatedData = $this->validator( $request->all() );
        if ( $validatedData->fails() ) {
            return response()->json( [ 'errors'=>$validatedData->errors() ], 400 );
        }

        $Location = new Location;

        $Location->user_id = $request[ 'user_id' ];
        $Location->laboratory_id = $request[ 'laboratory_id' ];
        $Location->latitude = $request[ 'latitude' ];
        $Location->longitude = $request[ 'longitude' ];
        $Location->save();

        return response()->json( $Location, 201 );
    }























}