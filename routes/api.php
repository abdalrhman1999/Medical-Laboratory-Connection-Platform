<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppTokenController;
use App\Http\Controllers\LabAnalysisController;
use App\Http\Controllers\UserAnalysisController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\OnlineDistanceController;
use App\Http\Controllers\LabLocController;
use App\Http\Controllers\UserDateLocController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StatisticsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//search
Route::get('/search-analysis', [AnalysisController::class, 'searchAnalysisName']);
Route::get('/search-nurse', [UserController::class, 'searchNurse']);
Route::get('/search-DrLaboratory', [UserController::class, 'searchLaboratory']);
Route::get('/search-employee', [UserController::class, 'searchEmployee']);
Route::get('/search-user', [UserController::class, 'searchUser']);
Route::get('/search-LabAnalysisName', [LabAnalysisController::class, 'searchlabAnalysesName']);
Route::get('/search-LabAnalysisprice', [LabAnalysisController::class, 'searchlabAnalysesprice']);
Route::get('/search-LabAnalysis', [LabAnalysisController::class, 'searchLabAnalysisName']);
Route::post('/confirm-appointment/{id}', [AppointmentController::class,'confirmAppointment']);
// role
Route::get('/role', [RoleController::class, 'getRole']);
Route::post('/role', [RoleController::class, 'addRole']);
Route::put('/role/{id}', [RoleController::class, 'updateRole']);
Route::delete('/role/{id}', [RoleController::class, 'deleteRole']);

//Location
   Route::post('/Location', [LocationController::class, 'addLocation']);
   Route::get('/Location', [LocationController::class, 'getLocation']);
   Route::get('/Location/{labId}', [LocationController::class, 'getLocationById']);

// User
Route::post('/register',[AuthController::class,'Register']);
Route::post('/add-admin',[AuthController::class,'addAdmin']);
Route::post('/login',[AuthController::class,'Login']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/add-employee',[AuthController::class,'addEmployee']);
    Route::post('/add-DrLaboratory',[AuthController::class,'addLaboratory']);
    Route::post('/add-nurse',[AuthController::class,'addNurse']);

    //analysis

    Route::post('/Analysis', [AnalysisController::class, 'addAnalysis']);
    Route::post('/LabAnalysis', [AnalysisController::class, 'addLabAnalysis']);
    Route::put('/Analysis/{id}', [AnalysisController::class, 'updateAnalysis']);
    Route::delete('/Analysis/{id}', [AnalysisController::class, 'deleteAnalysis']);
    Route::get('/Analysis', [AnalysisController::class, 'getAnalysis']);

    //nurse
    Route::put('/Nurse/{id}', [UserController::class, 'updateNurse']);
    Route::delete('/Nurse/{id}', [UserController::class, 'deleteNurse']);
    Route::get('/Nurse/{id}', [UserController::class, 'getNurse']);
    Route::get('/Nurses', [UserController::class, 'getNurses']);
    //المخبري
    Route::put('/DrLaboratory/{id}', [UserController::class, 'updateLaboratory']);
    Route::delete('/DrLaboratory/{id}', [UserController::class, 'deleteLaboratory']);
    Route::get('/DrLaboratory', [UserController::class, 'getLaboratory']);

    //Employee
    Route::put('/Employee/{id}', [UserController::class, 'updateEmployee']);
    Route::delete('/Employee/{id}', [UserController::class, 'deleteEmployee']);
    Route::get('/Employee', [UserController::class, 'getEmployee']);

    //User
    Route::post('/User', [UserController::class, 'updateUser']);
    Route::get('/User/{userid}', [UserController::class, 'getUser']);


    //المخبر
    Route::post('/Laboratory', [LaboratoryController::class, 'addLaboratory']);
    Route::put('/Laboratory/{id}', [LaboratoryController::class, 'updateLaboratory']);
    Route::get('/Laboratory', [LaboratoryController::class, 'getLaboratory']);
    Route::delete('/Laboratory/{id}', [LaboratoryController::class, 'deleteLaboratory']);
    Route::get('/search-laboratory', [LaboratoryController::class, 'searchLaboratory']);

    //LabAnalysis
    Route::post('/LabAnalysis', [LabAnalysisController::class, 'addLabAnalysis']);
    Route::put('/LabAnalysis/{id}', [LabAnalysisController::class, 'updateLabAnalysis']);
    Route::get('/LabAnalysis', [LabAnalysisController::class, 'getLabAnalysis']);
    Route::delete('/LabAnalysis/{id}', [LabAnalysisController::class, 'deleteLabAnalysis']);
    Route::get('/LabAnalysis/{id}', [LabAnalysisController::class, 'getAnalysis']);

    //UserAnalysis
    Route::post('/UserAnalysis', [UserAnalysisController::class, 'addUserAnalysis']);
    Route::put('/UserAnalysis/{id}', [UserAnalysisController::class, 'updateUserAnalysis']);
    Route::get('/UserAnalysis/{id}', [UserAnalysisController::class, 'getUserAnalysis']);
    Route::delete('/UserAnalysis/{id}', [UserAnalysisController::class, 'deleteUserAnalysis']);
    Route::get('/user-analysis/{user_id}', [UserAnalysisController::class, 'getAllUserAnalysis']);


    //appointment
    Route::post('/add-appointment', [AppointmentController::class,'AddAppointment']);
    Route::get('/show-appointments', [AppointmentController::class,'showAppointments']);
    Route::get('/nurse/appointments', [AppointmentController::class,'showNurseAppointments']);

    //Evaluation
    Route::post('/lab-Evaluation', [EvaluationController::class, 'Evaluate']);



   
    Route::post('/AddAppToken', [AppTokenController::class, 'AddAppToken']);

    //Statistics
    Route::get('/adminStatistics', [StatisticsController::class, 'getAdminStatistics']);
    Route::get('/staffStatistics', [StatisticsController::class, 'getStaffStatistics']);
    Route::get('/laboratoryStatistics', [StatisticsController::class, 'getLaboratoryStatistics']);



});


