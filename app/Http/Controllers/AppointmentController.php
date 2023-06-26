<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
// use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
// use App\Http\Controllers\JWTAuth;

class AppointmentController extends Controller
{
    use ApiResponseTrait;
    public function index(){
        $appointments=AppointmentResource::collection(Appointment::get());
        return $this->ApiResponse($appointments,"all done",200);
    }
    public function show($id){
        $appointments=Appointment::find($id);
        if($appointments){
        return $this->ApiResponse(new AppointmentResource($appointments),"all done",200);
        }
        return $this->ApiResponse(null,"appointment not found",404);
    }


public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'date' => 'required|date_format:Y-m-d H:i:s',
        'gender' => 'required|in:male,female',
        'hospital_id' => 'required|exists:hospitals,id',
    ]);
    // Check if the token is provided in the request headers
    if ($request->hasHeader('Authorization')) {
        // Extract the token from the Authorization header
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        $patient = auth()->guard('patient_api')->user();
        // Verify the token and get the authenticated patient
        // $patient = JWTAuth::toUser($token);
        // $patient = $this->jwt->User($token);
        // $patient = auth()->user();
        // $patient =JWTAuth::toUser($request->input('token'));
        // $patient =Authorization: `Bearer ${patientToken}`;

        // Add the patient's ID to the request data
        $validatedData['patient_id'] = $patient->id;
    }
    // Create a new appointment using the validated data
    $appointment = Appointment::create($validatedData);
    // Return a JSON response with the new appointment data
    return response()->json(['success' => true, 'data' => $appointment], 201);
}

    public function update(REQUEST $request,$id){
           //validation
        $validator=Validator::make($request->all(),[
            'name'=>'required|max:255',
            'phone'=>'required',
            'date'=>'required',
            'hospital_id'=>'required',
            'gender'=>'required'
        ]);
        if($validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }
        $appointment=Appointment::find($id);
        if(!$appointment){
            return $this->ApiResponse(null,"appointment not found",400);
        }

        $appointment->update($request->all());
        if($appointment){
            return $this->ApiResponse(new AppointmentResource($appointment),"appointment updated successfully",201);

        }
    }
/******************************************* */
    public function destroy($id){
        $appointment=Appointment::find($id);
        if(!$appointment){
            return $this->ApiResponse(null, "appointment not found",404);
        }
        $appointment->delete($id);

        if($appointment){
            return $this->ApiResponse(new AppointmentResource($appointment),"appointment deleted successfully",200);
        }
    }
}

/*************************************************** */

/*********************** */
// public function store(Request $request)
//     {
//         // Validate the request data
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|max:255',
//             'phone' => 'required',
//             'date' => 'required',
//             'gender' => 'required',
//             'hospital_id' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return $this->ApiResponse(null, $validator->errors(), 400);
//         }
//         // Create a new Appointment object with the request data
//         $appointment = new Appointment([
//             'name' => $request->name,
//             'phone' => $request->phone,
//             'date' => $request->date,
//             'gender' => $request->gender,
//             'hospital_id' => $request->hospital_id,
//             'patient_id' => auth()->id(),
//         ]);

//         // Save the new Appointment object to the database
//         $appointment->save();

//         // Return a response with the new Appointment object and a success message
//         return $this->ApiResponse(new AppointmentResource($appointment), "Appointment created successfully", 201);
//     }

    // public function store(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:255',
    //         'phone' => 'required',
    //         'date' => 'required',
    //         'gender' => 'required',
    //         'hospital_id' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->ApiResponse(null, $validator->errors(), 400);
    //     }
    //     $appointment = new Appointment($request->except('patient_id'));
    //     $appointment->patient_id =  $patient = auth()->guard('patient_api')->user();
    //     // auth()->id();
    //     if ($appointment->save()) {
    //         return $this->ApiResponse(new AppointmentResource($appointment), "Appointment created successfully", 201);
    //     }
    //     return $this->ApiResponse(null, "Appointment not saved", 400);
    // }
/******************************* */
   // public function store(REQUEST $request){
    //     //validation
    //     $validator=Validator::make($request->all(),[
    //         'name'=>'required|max:255',
    //         'phone'=>'required',
    //         'date'=>'required',
    //         'gender'=>'required',
    //         'hospital_id'=>'required',
    //         // 'patient_id'=>$this->patient_id,
    //     ]);
    //     if($validator->fails()){
    //         return $this->ApiResponse(null,$validator->errors(),400);
    //     }
    //     $appointments=Appointment::create($request->all());
    //     if($appointments){
    //         return $this->ApiResponse(new AppointmentResource($appointments),"appointments created successfully",201);
    //     }
    //     return $this->ApiResponse(null,"appointments not saved",400);
    // }
//     public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'name' => 'required|max:255',
//         'phone' => 'required',
//         'date' => 'required',
//         'gender' => 'required',
//         'hospital_id' => 'required',
//     ]);
//     if ($validator->fails()) {
//         return $this->ApiResponse(null, $validator->errors(), 400);
//     }
//     $appointment = new Appointment($request->except('patient_id'));
//     $appointment->patient_id = auth()->guard('patient_api')->user();
//     //  auth()->id();
//     $appointment->save();
//     return $this->ApiResponse(new AppointmentResource($appointment), "Appointment created successfully", 201);
// }
// public function store(Request $request){
// // Set up the request data
// $data = [
//     'name' => $request->name,
//     'phone' => $request->phone,
//     'date' => $request->date,
//     'gender' => $request->gender,
//     'hospital_id' => $request->hospital_id,
// ];
// // Set up the authorization header with the patient's token
// $headers = [
//     'Authorization' => 'Bearer '.$request->token,
// Authorization: `Bearer ${patientToken}`,
// ];
// // Make a POST request to the /api/appointments endpoint with the request data and headers
// $response = Http::withHeaders($headers)->post('http://127.0.0.1:8000/api/appointment', $data);
// // Check the response status code and return the response data
// if ($response->status() === 201) {
//     return response()->json(['success' => true, 'data' => $response->json()], 201);
// } else {
//     return response()->json(['success' => false, 'error' => $response->json()], $response->status());
// }

// }