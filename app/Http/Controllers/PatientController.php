<?php
namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Resources\MedicalRecordResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class PatientController extends Controller
{
    public function patientregister(REQUEST $request){
        $is_patient = $request->has('is_patient') ? ($request->is_patient ? 1 : 0) : 1;
        $patient=Patient::create([
        // 'id'=>$request->id,//
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
        'phone'=>$request->phone,
        'gender'=>$request->gender,
        'is_patient'=>$is_patient,
        // 'is_patient' => true, // set is_doctor to true by default
        // 'Hospital_id'=>$request->Hospital_id
    ]);
    if($patient){
        return response()->json([$patient,'status'=>true]);
    }
    else{
        return response()->json(['status'=>false]);
    }
    }
/************************************************ */
public function patientlog(REQUEST $request){
    $credentials = request(['email', 'password']);

    if (!$token = auth()->guard('patient_api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $patient = auth()->guard('patient_api')->user();

    if (!$patient || !$patient->is_patient) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json([
        'token' => $token,
        'patient' => $patient
    ]);
}
    // public function patientlog(REQUEST $request){
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth()->guard('patient_api')->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
    //     $x= response()->json(auth()->guard('patient_api')->user());
    // // $cookie = cookie('token', $token, 60);
    // return response()->json(['token' => $token,'info'=>$x]);
    // // ->cookie($cookie)
    // }
/**********************************************************************/
    public function patientme()
{
    if (!auth()->guard('patient_api')->check()) {
        return response()->json(null);
    }
    $patient = auth()->guard('patient_api')->user();
    return response()->json($patient);
}
/************************************** */
public function patientlogout()
    {
        auth()->guard('patient_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

/************************************** */
//     public function showMR($id){
//         $query = MedicalRecord::where('id','=',$id)->findOrFail($id);
//         return new MedicalRecordResource($query);
//     }
}