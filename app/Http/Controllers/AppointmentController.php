<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Analysis;
use App\Models\Laboratory;
use App\Models\Appointment;
use App\Models\AppTokenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller {

    public function showAppointments() {
        $user = Auth::user();
    
        if ($user->role_id != 3 && $user->role_id != 5) {
            return response()->json(['error' => 'You don\'t have permission.'], 422);
        }
    
        $appointments = DB::table('appointments')
            ->join('lab_analyses', 'appointments.lab_analyse_id', '=', 'lab_analyses.id')
            ->join('analyses', 'analyses.id', '=', 'lab_analyses.analysis_id')
            ->join('laboratories', 'laboratories.id', '=', 'lab_analyses.laboratory_id')
            ->join('users', 'users.id', '=', 'appointments.user_id')
            ->select(
                'appointments.id as appointment_id',
                'appointments.appointment_date',
                'appointments.kind',
                'appointments.lab_analyse_id',
                'appointments.confirmed',
                'users.name as user_name',
                'laboratories.lab_name',
                'analyses.name as analysis_name'
            )
            ->get();
    
            return response()->json(['data' => $appointments], 200);
            
                }
    

    public function AddAppointment(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'laboratory_id' => 'required|exists:laboratories,id',
            'analyse_ids' => 'required|array',
            'analyse_ids.*' => 'exists:analyses,id',
            'appointment_date' => 'required|string',
            'kind' => 'required',
            'nurse_id' => 'required_if:kind,1|exists:users,id',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }
    
        $user = Auth::user();
    
        if ($user->role_id != 5) {
            return response()->json(['error' => 'You don\'t have permission.'], 422);
        }
    
        try {
            $lab = Laboratory::findOrFail($request->laboratory_id);
            $analyseIds = $request->analyse_ids;
    
        
    
            foreach ($analyseIds as $analyseId) {
                $analyse = Analysis::findOrFail($analyseId);    
                $labAnalyse = $lab->labAnalyses()->where('analysis_id', $analyseId)->first();
                if (!$labAnalyse) {
                    return response()->json(['error' => 'The laboratory does not offer the specified analysis.'], 422);
                }
    
                $existingAppointment = Appointment::where('lab_analyse_id', $labAnalyse->id)
                    ->where('appointment_date', $request->appointment_date)
                    ->exists();
                if ($existingAppointment) {
                    return response()->json(['error' => 'There is an appointment on this date for one of the analyses.'], 422);
                }
    
                $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->appointment_date)->toDateString();
                $existingUserAppointment = Appointment::where('user_id', $user->id)
                    ->where('lab_analyse_id', $labAnalyse->id)
                    ->whereDate('appointment_date', $date)
                    ->exists();
                if ($existingUserAppointment) {
                    return response()->json(['error' => 'User already has an appointment on this date for one of the analyses.'], 422);
                }
    
                $appointment = new Appointment;
                $appointment->user_id = $user->id;
                $appointment->lab_analyse_id = $labAnalyse->id;
                $appointment->appointment_date = $request->appointment_date;
                $appointment->kind = $request->kind;
    
                if ($request->kind == '0') {

            $appointment->confirmed = 'yes';//مع عينة
                }
    
                $appointment->save();
    
                $userModel = User::find($user->id);
                $userModel->point = $userModel->point + $labAnalyse->point;
                $userModel->save();
            }
    
            $nurses = [];
            if ($request->kind == '1') {
                $nurses = User::where('laboratory_id', $lab->id)
                    ->where('role_id', 4)
                    ->get(['name']);
            }
            return response()->json(['data' => [
                'user' => $userModel->name,
                'laboratory' => $lab->lab_name,
                'analyses' => Analysis::whereIn('id', $analyseIds)->pluck('name'),
                'appointment_date' => $request->appointment_date,
                'nurses' => $nurses
            ]], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


        // $SERVER_API_KEY = 'AAAABTF30Lw:APA91bFSp0jTie__TDBFxIacdKoauejX_B7uKt8mhWiyk3SyAD9W_UQhHdjiWOPXptdTeKuF9I6jphP1X3E21fEbLgbr4MoFmOeE2f4kWppYURs3nnmDCyDtPvM2E8yDiF95rU5qOT1T';
        // $apptokens = AppTokenModel::where('user_id',$user->id )->get();
        // for ($i = 0; $i < count($apptokens); $i++) {
        //     $apptokens[$i] = $apptokens[$i]['token'];
        // }
        // return $user->id;
        // return $apptokens;

        //     // print( $apptokens);
        // $data = [

        //     "registration_ids" => $apptokens,
        //     "data" => [
        //         "test" => "test"
        //     ],
        //     "notification" => [

        //         "title" =>
        //         'New Appointment',

        //         "body" => 'check the updates',

        //         "sound" => "default"

        //     ],

        // ];

        // $dataString = json_encode($data);

        // $headers = [

        //     'Authorization: key=' . $SERVER_API_KEY,

        //     'Content-Type: application/json',

        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        // curl_setopt($ch, CURLOPT_POST, true);

        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);

        // dd($response);
 

    

            // $SERVER_API_KEY = 'AAAABTF30Lw:APA91bFSp0jTie__TDBFxIacdKoauejX_B7uKt8mhWiyk3SyAD9W_UQhHdjiWOPXptdTeKuF9I6jphP1X3E21fEbLgbr4MoFmOeE2f4kWppYURs3nnmDCyDtPvM2E8yDiF95rU5qOT1T';
            // $apptokens = AppTokenModel::where('user_id',$userid )->get();
            // for ($i = 0; $i < count($apptokens); $i++) {
            //     $apptokens[$i] = $apptokens[$i]['token'];
            // }
     

            // $data = [

            //     "registration_ids" => $apptokens,
            //     "data" => [
            //         "test" => "test"
            //     ],
            //     "notification" => [

            //         "title" =>
            //         'New Appointment',

            //         "body" => 'check the updates',

            //         "sound" => "default"

            //     ],

            // ];

            // $dataString = json_encode($data);

            // $headers = [

            //     'Authorization: key=' . $SERVER_API_KEY,

            //     'Content-Type: application/json',

            // ];

            // $ch = curl_init();

            // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            // curl_setopt($ch, CURLOPT_POST, true);

            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            // $response = curl_exec($ch);

// dd($response);
public function confirmAppointment( $id) {
        
    try {
        $appointments = Appointment::findOrFail( $id );
        if($appointments->confirmed == 'yes') {
            return response()->json( [ 'message'=>'the Appointment has already confirmed' ], 200 );
        } else { 
            $appointments->confirmed = 'yes';
            $appointments->save();
            return response()->json( [ 'message'=>'the Appointment has confirmed successfully' ], 200 );
        }

    } catch ( \Throwable $th ) {
        return response()->json( [ 'error'=>'Could\'nt find the appointment' ], 404 );
    }   
}

public function showNurseAppointments() {
    $user = Auth::user();

    if ($user->role_id != 4) {
        return response()->json(['error' => 'You don\'t have permission.'], 422);
    }

    $appointments = DB::table('appointments')
        ->join('lab_analyses', 'appointments.lab_analyse_id', '=', 'lab_analyses.id')
        ->join('analyses', 'analyses.id', '=', 'lab_analyses.analysis_id')
        ->join('laboratories', 'laboratories.id', '=', 'lab_analyses.laboratory_id')
        ->join('users', 'users.id', '=', 'appointments.user_id')
        ->join('locations', 'locations.user_id', '=', 'users.id') // تأكد من استخدام الاسم الصحيح للجدول
        ->select(
            'appointments.id as appointment_id',
            'appointments.appointment_date',
            'appointments.kind',
            'appointments.confirmed',
            'users.name as user_name',
            'laboratories.lab_name',
            'analyses.name as analysis_name',
            'locations.latitude',
            'locations.longitude'
        )
        ->where('appointments.confirmed', '=', 'yes')
        ->where('appointments.kind', '=', '1')
        ->get();

    return response()->json(['data' => $appointments], 200);
}

}
