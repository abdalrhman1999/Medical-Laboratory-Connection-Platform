<?php
namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Evaluation;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller {

    public function Evaluate( Request $request ) {
        Validator::make( $request->all(), [
            'laboratory_id'=>'required',
            'evaluation'=>'required|numeric|min:1|max:5',
        ] );

        try {
            $user = Auth::user()->id;
            $evaluation = new Evaluation();
            $evaluation->user_id = $user;
            $evaluation->laboratory_id = $request->laboratory_id;
            $evaluation->evaluation = $request->evaluation;
            $evaluation->save();

            $oldEval =  Evaluation::where( 'laboratory_id', $request->laboratory_id );
            $lab =  Laboratory::where( 'id', $request->laboratory_id )->first();

            if ( $lab->score == '0' ) {
                $lab->score = $request->evaluation;
            } else {
                $temp = $roundedNumber = round( $oldEval->sum( 'evaluation' ) /$oldEval->count(), 1 );
                $lab->score = $temp;
            }
            $lab->save();

            return response()->json( [
                'message'=>'The laboratory are Evaluated Successfully'
            ], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'error'=>$th->getMessage()
            ], 500 );
        }

    }

}
