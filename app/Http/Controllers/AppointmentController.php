<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Hospital;
use App\Enums\AppointmentStatusEnum;//
use Tymon\JWTAuth\Facades\JWTAuth;
class AppointmentController extends Controller
{
    use ApiResponseTrait;
    //******************************************كل المواعيد****/
    public function index(){
        $appointments=AppointmentResource::collection(Appointment::get());
        return $this->ApiResponse($appointments,"all done",200);
    }

    //******************************************ميعاد محدد***/
    public function show($id){
        $appointments=Appointment::find($id);
        if($appointments){
        return $this->ApiResponse(new AppointmentResource($appointments),"all done",200);
        }
        return $this->ApiResponse(null,"appointment not found",404);
    }
    //*********************************اخزن او اضيف مواعيد في الداتا بيز *************/
public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'date' => 'required|date_format:Y-m-d H:i:s',
        'gender' => 'required|in:male,female',
        'Hospital_id' => 'required|exists:hospitals,id',
        'status' => 'nullable|in:scheduled,closed',
    ]);
    // Check if the token is provided in the request headers
    if ($request->hasHeader('Authorization')) {
        // Extract the token from the Authorization header
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        $patient = auth()->guard('patient_api')->user();
        // Add the patient's ID to the request data
        $validatedData['patient_id'] = $patient->id;
    }
    // Set the default value of the status field if it is not present in the request data
    if (!isset($validatedData['status'])) {
        $validatedData['status'] = AppointmentStatusEnum::Scheduled;
    }
    // Create a new appointment using the validated data
    $appointment = Appointment::create($validatedData);

    // Return a JSON response with the new appointment data, including the status field
    if ($appointment) {
        return response()->json(['success' => true, 'data' => $appointment], 201);
    }
    else{
    return response()->json(['status'=>false]);
    }
}
/*******************************تحدث وتعدل ميعاد************************ */
    public function update(REQUEST $request,$id){
           //validation
        $validator=Validator::make($request->all(),[
            'name'=>'required|max:255',
            'phone'=>'required',
            'date'=>'required',
            'Hospital_id'=>'required',
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
        }}
/********************************تكنسل او تلغي ميعاد *********** */
    public function destroy($id){
        $appointment=Appointment::find($id);
        if(!$appointment){
            return $this->ApiResponse(null, "appointment not found",404);
        }
        $appointment->delete($id);
        if($appointment){
            return $this->ApiResponse(new AppointmentResource($appointment),"appointment deleted successfully",200);
        }}
/*********************************** idتجيب كل المستشفيات اللي ليهم نفس  ****************** */
public function getHospitalAppointments($hospitalId)
{
    $appointments = Appointment::where('hospital_id', $hospitalId)
        ->get();
    if ($appointments->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No appointments found for this hospital ID'
        ]);
    }
    $output = $appointments->toArray();
    return response()->json($output);
}
/************************************************ */
public function getHospitalPatients($Hospital_id)
{
    // Retrieve all patients with the provided hospital_id
    $patients = Patient::where('hospital_id', $Hospital_id)->get();

    // Map the patients to the desired output format
    $output = $patients->map(function ($patient) {
        return [
            'id' => $patient->id,
            'name' => $patient->name,
            'phone' => $patient->phone,
            'gender' => $patient->gender,
            'created_at' => $patient->created_at,
            'updated_at' => $patient->updated_at,
            'hospital_id' => $patient->hospital_id
        ];
    });

    // Return the output as a JSON response
    return response()->json($output);
}
/******************************* */
public function getHospitalPatientsAppointments($hospital_id)
{
    $patients = Patient::where('hospital_id', $hospital_id)->get();

    // Map the patients to the desired output format
    $output = $patients->map(function ($patient) {
        // Retrieve all appointments for the current patient
        $appointments = Appointment::where('patient_id', $patient->id)->get();

        // Map the appointments to the desired output format
        $appointments_output = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'date' => $appointment->date,
                'status' => $appointment->status,
                'Hospital_id' => $appointment->Hospital_id,
            ];
        });

        return [
            'id' => $patient->id,
            'name' => $patient->name,
            'email' => $patient->email,
            'phone' => $patient->phone,
            'gender' => $patient->gender,
            'is_patient' => true,
            'created_at' => $patient->created_at,
            'updated_at' => $patient->updated_at,
            'Hospital_id' => $patient->Hospital_id,
            'appointments' => $appointments_output,
        ];
    });

    // Return the output as a JSON response
    return response()->json(['patients' => $output]);
}
/*************************************************** */
    }
/*************************************************** */

// public function getHospitalAppointments(Request $request, $Hospital_id)
// {
//     $appointments = Appointment::with('patient')
//                                 ->where('Hospital_id', $Hospital_id)
//                                 ->get();
//     $output = $appointments->map(function ($appointment) {
//         return [
//             'name' => $appointment->patient->name,
//             'phone' => $appointment->patient->phone,
//             'date' => $appointment->date_time,
//             'gender' => $appointment->patient->gender,
//             'Hospital_id' => $appointment->Hospital_id,
//             'patient_id' => $appointment->patient_id,
//             'updated_at' => $appointment->updated_at,
//             'created_at' => $appointment->created_at,
//             'id' => $appointment->id,
//             'status' => $appointment->status
//         ];
//     });
//     // Return the output as a JSON response
//     return response()->json($output);
// }
     //name gender phone patient_id doctor_id  hospital_id date status
//     public function store(Request $request){
//         // Validate the request data
//         $validatedData = $request->validate([
//             'name' => 'required|string|max:255',
//             'phone' => 'required|string|max:15',
//             'date' => 'required|date_format:Y-m-d H:i:s',
//             'gender' => 'required|in:male,female',
//             'Hospital_id' => 'required|exists:hospitals,id',
//             // 'status'=>$this->Scheduled
//         ]);
//     // Check if the token is provided in the request headers
//     if ($request->hasHeader('Authorization')) {
//         // Extract the token from the Authorization header
//         $token = str_replace('Bearer ', '', $request->header('Authorization'));
//         $patient = auth()->guard('patient_api')->user();
//         // Verify the token and get the authenticated patient
//         // $patient = JWTAuth::toUser($token);
//         // $patient = $this->jwt->User($token);
//         // $patient = auth()->user();
//         // $patient =JWTAuth::toUser($request->input('token'));
//         // $patient =Authorization: `Bearer ${patientToken}`;

//         // Add the patient's ID to the request data
//         $validatedData['patient_id'] = $patient->id;
//     }
//     // Create a new appointment using the validated data
//     $appointment = Appointment::create($validatedData);
//     // Return a JSON response with the new appointment data
//     return response()->json(['success' => true, 'data' => $appointment], 201);
// }

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
