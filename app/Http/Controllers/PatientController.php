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
        $patient=Patient::create([
        // 'id'=>$request->id,//
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
        'phone'=>$request->phone,
        'gender'=>$request->gender,
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

        if (! $token = auth()->guard('patient_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $x= response()->json(auth()->guard('patient_api')->user());
    // $cookie = cookie('token', $token, 60);
    return response()->json(['token' => $token,'info'=>$x]);
    // ->cookie($cookie)
    }
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
//************************************ */
// use Tymon\JWTAuth\Facades\JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use App\Http\Controllers\Exception;
    // if (! $token= auth()->guard('patient_api')){
    //     return response()->json(null);}
    // else{
    // public function patientme()
    // {
    //     return response()->json(auth()->guard('patient_api')->user());
    // }

    // public function patientme()
    // {
    //     // $cookie = cookie('token', '', -1);
    //     // if ($cookie ) {
    //     // Check if the token is present in the request
    //     if (!auth()->guard('patient_api')->check()) {

    //         // If the token is not present, return a null response
    //         return response()->json(null);
    //     }}

    // public function patientme(Request $request)
    // {
    // // try {
    //     $token = $request->cookie('token');
    //     if (!$token) {
    //         return response()->json(null);
    //     }
    //     // $user = JWTAuth::parseToken()->authenticate();
    //     // return response()->json([
    //     //     'username' => $user->username,
    //     //     'id' => $user->id,
    //     // ]);
    //     return response()->json(auth()->guard('patient_api')->user());
    // // } catch (Exception $e) {
    // //     return response()
    // //         ->cookie('token', '')
    // //         ->json(null);
    // }




/******************************************************* */

// public function patientme()
// {
//         // Retrieve the authenticated patient using the 'patient_api' guard
//         $patient = auth()->guard('patient_api')->user();

//         // Return a JSON response with the patient's information
//         return response()->json($patient);
//     }

/****************************************** */
        // $query = MedicalRecord::all();
        // $query = MedicalRecord::query()->where("id",$id);
        // $query = MedicalRecord::where("id", $id)->pluck("name", "id");
        // query()->
        // $list_sections = Section::where("Class_id", $id)->pluck("Name_Section", "id");
        // return $query;
        // return MedicalRecordResource::collection($query);
        // return new MedicalRecordResource($this->$query);
        // return new MedicalRecordResource($query);
        // $query = MedicalRecord::where('id','=',auth()->user()->id)->findOrFail($id);

    // return MedicalRecordResource::make($query);




/********************************* */

//     public function patientlogout()
// {
//     auth()->guard('patient_api')->logout();

//     // Remove the 'token' cookie by creating a new cookie with an empty value and an expiration time in the past
//     $cookie = cookie('token', '', -1);

//     // Return a JSON response indicating that the logout was successful, with the 'token' cookie included to remove it from the client's browser
//     return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
// }



// public function showMR($id){

        // $name = request('name');
        // $percentageofmodel = request('percentageofmodel', '');
        // $Hospital_id = request('Hospital_id');
        // $patient_id = request('patient_id');

        // $query = MedicalRecord::query()->where("patient_id", $request);
            // ->where('id', '=', $patient_id,$Hospital_id);
            // ->orderBy($sortField, $sortDirection);


            // $MedicalRecord = MedicalRecord::where("patient_id", $id);
            // ->pluck("percentageofmodel", "id");
            // if($MedicalRecord){
            //     return $this->ApiResponse(new MedicalRecordResource($MedicalRecord),"all done",200);
            // }
            // return $this->ApiResponse(null,"MedicalRecord not found",404);
// }

    // return response()->cookie('token', '',60)->json([ 'valid' => auth()->check() ])->json(['message' => 'Logged out successfully']);
        // return response()
        // ->cookie('token', '',60)
        // ->json(['message' => 'Logged out successfully']);
    // }

    // return response()->json([ 'valid' => auth()->check() ]);
//     public function patientlogout(Request $request)
// {
//     auth()->guard('patient_api')->logout();

//     return response()
//         ->cookie('token', '', 1) // set expiration time to 1 minute ago
//         ->json(['message' => 'Logged out successfully']);
// }

    // $cookie = cookie('token', $token, 60);

    // Logout endpoint
// Route::post('/logout', function (Request $request) {
//     return response()
//         ->cookie('token', '')
//         ->json(['message' => 'Logged out successfully']);
// });