<?php

namespace App\Http\Controllers;

use App\Models\AppTokenModel;
use Illuminate\Http\Request;

class AppTokenController extends Controller
{
    public function AddAppToken(Request $request){
        $appToken =new AppTokenModel();
        $appToken->token = $request->token;
        $appToken->user_id = $request->user_id;
      $appToken =  $appToken->save();
      if($appToken){
        return response()->json( [
            'status' => "success"
        ],200 );
      }
        
    }
}
