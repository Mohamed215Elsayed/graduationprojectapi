<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicalReportController;
// use \Mcamara\LaravelLocalization\Facades\LaravelLocalization;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//doctor routes
Route::post('doctorregister',[DoctorController::class,'doctorregister'])->name('doctorregister');
Route::post('doctorlog', [DoctorController::class,'doctorlog'])->name('doctorlog');
Route::group([
    'middleware' => 'doctor:doctor_api',
], function () {
    Route::post('doctorme',[DoctorController::class,'doctorme'])->name('doctorme');
    Route::post('doctorlogout',[DoctorController::class,'doctorlogout'])->name('doctorlogout');
});



//patient routes
Route::post('patientregister',[PatientController::class,'patientregister'])->name('patientregister');
Route::post('patientlog', [PatientController::class,'patientlog'])->name('patientlog');
Route::get('/patient/{id}',[PatientController::class,'showMR']);//
// Route::post('patientlogout',[PatientController::class,'patientlogout'])->name('patientlogout');
// Route::post('patientme',[PatientController::class,'patientme'])->name('patientme');
Route::group([
    'middleware' => 'patient:patient_api',
], function () {
    Route::post('patientme',[PatientController::class,'patientme'])->name('patientme');
    Route::post('patientlogout',[PatientController::class,'patientlogout'])->name('patientlogout');
});

//admin route
Route::post('adminregister',[AdminController::class,'adminregister'])->name('adminregister');
Route::post('adminlog', [AdminController::class,'adminlog'])->name('adminlog');
Route::group([
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'admin:admin_api']]
//     [
//     'middleware' => 'admin:admin_api',
// ]
, function () {
    Route::post('adminme',[AdminController::class,'adminme'])->name('adminme');
    Route::post('adminlogout',[AdminController::class,'adminlogout'])->name('adminlogout');
        //hospital routes
    Route::get('/hospitals',[HospitalController::class,'index']);
    Route::get('/hospitals/{id}',[HospitalController::class,'show']);
    Route::post('/hospitals',[HospitalController::class,'store']);
    // Route::post('/hospitals/{id}',[HospitalController::class,'update']);
    Route::post('/hospital/{id}',[HospitalController::class,'destroy']);
//appointment route
Route::get('/appointments',[AppointmentController::class,'index']);
Route::get('/appointment/{id}',[AppointmentController::class,'show']);
Route::post('/appointment',[AppointmentController::class,'store']);
Route::post('/appointments/{id}',[AppointmentController::class,'update']);
Route::post('/appointment/{id}',[AppointmentController::class,'destroy']);
//medicalrecords route
Route::get('/medicalrecords',[MedicalRecordController::class,'index']);
Route::get('/medicalrecord/{id}',[MedicalRecordController::class,'show']);
Route::post('/medicalrecords',[MedicalRecordController::class,'store']);
Route::post('/medicalrecords/{id}',[MedicalRecordController::class,'update']);
Route::post('/medicalrecord/{id}',[MedicalRecordController::class,'destroy']);
Route::get('/medicalrecords/{id}',[MedicalRecordController::class,'showMR']);//
// Route::get('medicalrecordss',[MedicalRecordController::class,'getPercentageOfModelList']);//
Route::get('medicalrecordss',[MedicalRecordController::class,'getPatientPercentageList']);//
//medicalreports route
Route::get('/medicalreports',[MedicalReportController::class,'index']);
Route::get('/medicalreport/{id}',[MedicalReportController::class,'show']);
Route::post('/medicalreports',[MedicalReportController::class,'store']);
Route::post('/medicalreports/{id}',[MedicalReportController::class,'update']);
Route::post('/medicalreport/{id}',[MedicalReportController::class,'destroy']);
});
///////////////////////////////////////////////////////////////////////
//hospital routes
Route::get('/hospitals',[HospitalController::class,'index']);
Route::get('/hospitals/{id}',[HospitalController::class,'show']);
Route::post('/hospitals',[HospitalController::class,'store']);
// Route::post('/hospitals/{id}',[HospitalController::class,'update']);
Route::post('/hospital/{id}',[HospitalController::class,'destroy']);
///////////////////////////////////////////////////////////////////////
//appointment route
Route::get('/appointments',[AppointmentController::class,'index']);
Route::get('/appointment/{id}',[AppointmentController::class,'show']);
Route::post('/appointment',[AppointmentController::class,'store']);
Route::post('/appointments/{id}',[AppointmentController::class,'update']);
Route::post('/appointment/{id}',[AppointmentController::class,'destroy']);
///////////////////////////////////////////////////////////////////////
//medicalrecords route
Route::get('/medicalrecords',[MedicalRecordController::class,'index']);
Route::get('/medicalrecord/{id}',[MedicalRecordController::class,'show']);
Route::post('/medicalrecords',[MedicalRecordController::class,'store']);
Route::post('/medicalrecords/{id}',[MedicalRecordController::class,'update']);
Route::post('/medicalrecord/{id}',[MedicalRecordController::class,'destroy']);
Route::get('/medicalrecords/{id}',[MedicalRecordController::class,'showMR']);//
// Route::get('medicalrecordss',[MedicalRecordController::class,'getPercentageOfModelList']);//
Route::get('medicalrecordss',[MedicalRecordController::class,'getPatientPercentageList']);//
///////////////////////////////////////////////////////////////////////////
//medicalreports route
Route::get('/medicalreports',[MedicalReportController::class,'index']);
Route::get('/medicalreport/{id}',[MedicalReportController::class,'show']);
Route::post('/medicalreports',[MedicalReportController::class,'store']);
Route::post('/medicalreports/{id}',[MedicalReportController::class,'update']);
Route::post('/medicalreport/{id}',[MedicalReportController::class,'destroy']);
/////////////////////////////////////////////////////////////////////////////////////////