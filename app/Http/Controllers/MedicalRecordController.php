<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalRecordResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Patient;
// use App\Http\Resources\MedicalReportResource;
// use App\Models\MedicalReport;

class MedicalRecordController extends Controller
{
    //
    use ApiResponseTrait;
    public function index(){
        // $posts=Post::get();
        $MedicalRecords=MedicalRecordResource::collection(MedicalRecord::get());
        //way 1
        // $array=[
        //     'data'=>$posts,
        //     'message'=>"ok",
        //     'status'=>200
        // ];
        // return response($array);

        //way 2
        // $msg=["ok"];
        // return response($posts,$status=200,$msg);

        //way 3
        return $this->ApiResponse($MedicalRecords,"all done",200);

    }

    public function show($id){
        // $post=Post::find($id);
        $MedicalRecord=MedicalRecord::find($id);
        if($MedicalRecord){
        return $this->ApiResponse(new MedicalRecordResource($MedicalRecord),"all done",200);
        }
        return $this->ApiResponse(null,"MedicalRecord not found",404);
    }

    //id name  percentageofmodel  percentageofdoctor  Hospital_id  patient_id


    public function store(REQUEST $request){
        //validation
        $validator=Validator::make($request->all(),[
            'name'=>'required|max:255',
            'percentageofmodel'=>'required',
            'percentageofdoctor'=>'required',
            'Hospital_id'=>'required',
            'patient_id'=>'required',
        ]);
        if($validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }


        $MedicalRecords=MedicalRecord::create($request->all());
        if($MedicalRecords){
            return $this->ApiResponse(new MedicalRecordResource($MedicalRecords),"MedicalRecord created successfully",201);
        }
         return $this->ApiResponse(null,"MedicalRecord not saved",400);

    }

    public function update(REQUEST $request,$id){
           //validation
           $validator=Validator::make($request->all(),[
            'name'=>'required|max:255',
            'percentageofmodel'=>'required',
            'percentageofdoctor'=>'required',
            // 'Hospital_id'=>'required',
            // 'patient_id'=>'required',
         ]);
         if($validator->fails()){
             return $this->ApiResponse(null,$validator->errors(),400);
         }
         $MedicalRecord=MedicalRecord::find($id);
         if(!$MedicalRecord){
            return $this->ApiResponse(null,"MedicalRecord not found",400);
        }

        $MedicalRecord->update($request->all());
         if($MedicalRecord){
            return $this->ApiResponse(new MedicalRecordResource($MedicalRecord),"MedicalRecord updated successfully",201);

         }
    }

    public function destroy($id){
        $MedicalRecord=MedicalRecord::find($id);
        if(!$MedicalRecord){
            return $this->ApiResponse(null, "MedicalRecord not found",404);
        }
        $MedicalRecord->delete($id);

        if($MedicalRecord){
            return $this->ApiResponse(new MedicalRecordResource($MedicalRecord),"MedicalRecord deleted successfully",200);
        }}
        // فانكشن بتاخد ميديكل ريكورد  اي دي وبترجع الشخص وال اي دي لهذا الميديكل ريكورد
    public function showMR($id){
            // $query = MedicalRecord::where('id','=',$id)->findOrFail($id);
            // return new MedicalRecordResource($query);
            $query = Patient::where('id','=',$id)->findOrFail($id)->pluck("name", "id");
            return($query);
    }

    public function getPatientPercentageList(){
        $medicalRecords = MedicalRecord::all();
        $patientPercentageList = [];

        // Define scheduling constraints
        $startTime = strtotime('10:00');
        $endTime = strtotime('17:00'); // 5:00 PM
        $interval = 1800; // 30 minutes in seconds

        // Sort medical records by descending percentageofmodel value
        $medicalRecords = $medicalRecords->sortByDesc('percentageofmodel');

        // Iterate over medical records and schedule appointments for each patient
        foreach ($medicalRecords as $record) {
        $patient_id = $record->patient_id;
        $percentageofmodel = $record->percentageofmodel;

        // Initialize appointment time to null
        $appointment_time = null;

        // Iterate over available time slots and find the first available slot that doesn't conflict
        for ($time = $startTime; $time <= $endTime; $time += $interval) {
            $formattedTime = date('h:i A', $time);
            $conflictFound = false;

            // Check if there are any conflicting appointments at this time slot
            foreach ($patientPercentageList as $existingAppointment) {
                if ($existingAppointment['time'] == $formattedTime) {
                    $conflictFound = true;
                    break;
                }
            }

            if (!$conflictFound) {
                $appointment_time = $formattedTime;
                break;
            }
        }

        // Add appointment tuple to list if a slot was found
        if ($appointment_time !== null) {
            $tuple = [
                'patient_id' => $patient_id,
                'percentageofmodel' => $percentageofmodel,
                'time' => $appointment_time,
            ];
            $patientPercentageList[] = $tuple;
        }
    }

    // Sort the list of tuples by descending percentageofmodel value
    usort($patientPercentageList, function ($a, $b) {
        return $b['percentageofmodel'] - $a['percentageofmodel'];
    });

    return $patientPercentageList;
}
}


    // public function getPercentageOfModelList() {
    //     $x=MedicalRecord::all()->pluck('percentageofmodel')->all();
    //     return $x;
    // }
    /**************** */
    // public function getPercentageOfModel(Request $request) {
    //     $percentageofmodel = $request->input('percentageofmodel');
    //     return $percentageofmodel;
    // }
    // public function getPercentageOfModel($id) {
    //     $percentageofmodel = MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();
    //     return $percentageofmodel;
    // }
    // public function getPercentageOfModel($id) {
    //     $percentageofmodel = MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();

    //     // Add $percentageofmodel to a list
    //     $percentageList = [];
    //     if (!is_null($percentageofmodel)) {
    //         $percentageList[] = $percentageofmodel;
    //     }

    //     // Sort the list in descending order
    //     arsort($percentageList);

    //     return $percentageList;
    // }

    // public function showpercentage(REQUEST $request){
    //     $query = MedicalRecord::find($percentageofmodel);
    //     // where('id','=',$id)->findOrFail($id)->pluck("name", "id");
    //     return($query);
//     public function showpercentage(Request $request){
//         $query = MedicalRecord::find($percentageofmodel);
//         $publicProperties = get_object_vars($query);
// }
// public function getPercentageOfModel(array $ids) {
//     $percentageList = [];

//     foreach ($ids as $id) {
//         $percentageofmodel = MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();
//         if (!is_null($percentageofmodel)) {
//             $percentageList[] = $percentageofmodel;
//         }
//     }

//     // Sort the list in descending order
//     arsort($percentageList);

//     return $percentageList;
// }

// public function someFunction($id) {
//     $x=MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();
//     // Example usage of getPercentageOfModel function with an array of IDs
//     $ids = []; // Example array of IDs
//     $y = array_push($ids,$x);
//     // $percentageList = $this->getPercentageOfModel($ids); // Call the function with the array of IDs
//     // Do something with the sorted list of $percentageofmodel values
//     return $y;
// }

// public function getPercentageOfModel(array $ids) {
//     $percentageList = [];

//     foreach ($ids as $id) {
//         $percentageofmodel = MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();
//         if (!is_null($percentageofmodel)) {
//             $percentageList[] = $percentageofmodel;
//         }
//     }

//     // Sort the list in descending order
//     arsort($percentageList);

//     return $percentageList;
// }
// public function getPercentageOfModelList()
// {
//     $medicalRecords = MedicalRecord::all();
//     $percentageOfModelList = [];

//     foreach ($medicalRecords as $record) {
//         $percentageOfModelList[] = $record->percentageofmodel;
//     }

//     rsort($percentageOfModelList);
//     // $percentageOfModelList = $this->getPercentageOfModelList();
//     $percentageOfModelList = $this->getPercentageOfModelList();
// print_r($percentageOfModelList); // Output: [80, 12]

//     // return $percentageOfModelList;
// }


        // $Y=array_push($x);
        // $x.SORT_DESC;
    // $x=[];
        // $x=MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();
    //     // Example usage of getPercentageOfModel function with an array of IDs
    //     $ids = []; // Example array of IDs
        // $y = array_push($ids,$x);
    //     // $percentageList = $this->getPercentageOfModel($ids); // Call the function with the array of IDs
    //     // Do something with the sorted list of $percentageofmodel values
    //     return $y;

    // $x=MedicalRecord::where('id', $id)->pluck('percentageofmodel')->first();
    // $x=[];




//     public function getPatientPercentageList()
// {
//     $medicalRecords = MedicalRecord::all();
//     $patientPercentageList = [];

//     foreach ($medicalRecords as $record) {
//         $tuple = [
//             'patient_id' => $record->patient_id,
//             'percentageofmodel' => $record->percentageofmodel,
//         ];

//         $patientPercentageList[] = $tuple;
//     }

//     usort($patientPercentageList, function ($a, $b) {
//         return $b['percentageofmodel'] - $a['percentageofmodel'];
//     });

//     return $patientPercentageList;
// }

// public function getPatientPercentageList()
// {
//     $medicalRecords = MedicalRecord::all();
//     $patientPercentageList = [];

//     $startTime = strtotime('10:00');
//     $endTime = strtotime('19:30'); // 7:30 PM
//     $interval = 1800; // 30 minutes in seconds

//     foreach ($medicalRecords as $record) {
//         $tuple = [
//             'patient_id' => $record->patient_id,
//             'percentageofmodel' => $record->percentageofmodel,
//             'time' => null,
//         ];

//         // Calculate the next available time slot for this patient
//         $time = $startTime;
//         $conflictFound = false;
//         while (!$conflictFound && $time <= $endTime) {
//             $formattedTime = date('h:i A', $time);
//             $conflictFound = false;

//             // Check if there are any conflicting appointments at this time slot
//             foreach ($patientPercentageList as $existingAppointment) {
//                 if ($existingAppointment['time'] == $formattedTime) {
//                     $conflictFound = true;
//                     break;
//                 }
//             }

//             if (!$conflictFound) {
//                 $tuple['time'] = $formattedTime;
//                 $patientPercentageList[] = $tuple;
//                 break;
//             }

//             $time += $interval;
//         }
//     }

//     usort($patientPercentageList, function ($a, $b) {
//         return $b['percentageofmodel'] - $a['percentageofmodel'];
//     });

//     return $patientPercentageList;
// }

// public function getPatientPercentageList()
// {
//     $medicalRecords = MedicalRecord::all();
//     $patientPercentageList = [];

//     $startTime = strtotime('10:00');
//     $endTime = strtotime('19:30'); // 7:30 PM
//     $interval = 1800; // 30 minutes in seconds

//     foreach ($medicalRecords as $record) {
//         $tuple = [
//             'patient_id' => $record->patient_id,
//             'percentageofmodel' => $record->percentageofmodel,
//             'time' => null,
//         ];

//         // Calculate the next available time slot for this patient
//         $time = $startTime;
//         $conflictFound = false;
//         while (!$conflictFound && $time <= $endTime) {
//             $formattedTime = date('h:i A', $time);
//             $conflictFound = false;

//             // Check if there are any conflicting appointments at this time slot
//             foreach ($patientPercentageList as $existingAppointment) {
//                 if ($existingAppointment['time'] == $formattedTime) {
//                     $conflictFound = true;
//                     break;
//                 }
//             }

//             if (!$conflictFound) {
//                 $tuple['time'] = $formattedTime;
//                 $patientPercentageList[] = $tuple;
//                 break;
//             }

//             $time += $interval;
//         }
//     }

//     usort($patientPercentageList, function ($a, $b) {
//         return $b['percentageofmodel'] - $a['percentageofmodel'];
//     });

//     return $patientPercentageList;
//     // $patientPercentageList = $this->getPatientPercentageList();
// // print_r($patientPercentageList);
// }

// public function getPatientPercentageList()
// {
//     // Retrieve all MedicalRecord instances with non-null percentageofmodel values
//     $medicalRecords = MedicalRecord::whereNotNull('percentageofmodel')->get();
//     $patientPercentageList = [];

//     $startTime = strtotime('10:00');
//     $endTime = strtotime('19:00'); // 7:00 PM
//     $interval = 1800; // 30 minutes in seconds

//     foreach ($medicalRecords as $record) {
//         $tuple = [
//             'patient_id' => $record->patient_id,
//             'percentageofmodel' => $record->percentageofmodel,
//             'time' => null,
//         ];

//         // Calculate the next available time slot for this patient
//         $time = $startTime;
//         $conflictFound = false;
//         while (!$conflictFound && $time <= $endTime) {
//             $formattedTime = date('h:i A', $time);
//             $conflictFound = false;

//             // Check if there are any conflicting appointments at this time slot
//             foreach ($patientPercentageList as $existingAppointment) {
//                 if ($existingAppointment['time'] == $formattedTime) {
//                     $conflictFound = true;
//                     break;
//                 }
//             }

//             if (!$conflictFound) {
//                 $tuple['time'] = $formattedTime;
//                 $patientPercentageList[] = $tuple;
//                 break;
//             }

//             $time += $interval;
//         }

//         // If no time slot was found, set the time to null
//         if ($tuple['time'] === null) {
//             continue;
//         }

//         // Check if the calculated time slot is within the allowed time range
//         $timeStamp = strtotime($tuple['time']);
//         if ($timeStamp < $startTime || $timeStamp > $endTime) {
//             $tuple['time'] = null;
//             continue;
//         }
//     }

//     // Sort the list of tuples by descending percentageofmodel value
//     usort($patientPercentageList, function ($a, $b) {
//         return $b['percentageofmodel'] - $a['percentageofmodel'];
//     });

//     return $patientPercentageList;
// }
// Modified getPatientPercentageList() function
// public function getPatientPercentageList()
// {
//     // Retrieve all MedicalRecord instances with non-null percentageofmodel values
//     $medicalRecords = MedicalRecord::whereNotNull('percentageofmodel')->get();
//     $patientPercentageList = [];

//     $startTime = strtotime('10:00');
//     $endTime = strtotime('19:00'); // 7:00 PM
//     $interval = 1800; // 30 minutes in seconds

//     foreach ($medicalRecords as $record) {
//         $tuple = [
//             'patient_id' => $record->patient_id,
//             'percentageofmodel' => $record->percentageofmodel,
//             'time' => null,
//         ];

//         // Calculate the next available time slot for this patient
//         $time = $startTime;
//         $conflictFound = false;
//         while (!$conflictFound && $time <= $endTime) {
//             $formattedTime = date('h:i A', $time);
//             $conflictFound = false;

//             // Check if there are any conflicting appointments at this time slot
//             foreach ($patientPercentageList as $existingAppointment) {
//                 if ($existingAppointment['time'] == $formattedTime) {
//                     $conflictFound = true;
//                     break;
//                 }
//             }

//             if (!$conflictFound) {
//                 $tuple['time'] = $formattedTime;
//                 $patientPercentageList[] = $tuple;
//                 break;
//             }

//             $time += $interval;
//         }

//         // If no time slot was found, set the time to null
//         if ($tuple['time'] === null) {
//             continue;
//         }

//         // Check if the calculated time slot is within the allowed time range
//         $timeStamp = strtotime($tuple['time']);
//         if ($timeStamp < $startTime || $timeStamp > $endTime) {
//             $tuple['time'] = null;
//             continue;
//         }
//     }

//     // Sort the list of tuples by descending percentageofmodel value
//     usort($patientPercentageList, function ($a, $b) {
//         return $b['percentageofmodel'] - $a['percentageofmodel'];
//     });

//     return $patientPercentageList;
// }

// // // Example usage of the getPatientPercentageList() function
// // $patientPercentageList = $this->getPatientPercentageList();

// // if (count($patientPercentageList) > 0) {
// //     echo "Appointments:\n\n";
// //     foreach ($patientPercentageList as $tuple) {
// //         echo "Patient ID: " . $tuple['patient_id'] . "\n";
// //         echo "Percentage of Model: " . $tuple['percentageofmodel'] . "\n";
// //         echo "Time Slot: " . $tuple['time'] . "\n\n";
// //     }
// // } else {
// //     echo "No appointments could be scheduled.\n";
// // }