<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalReportResource;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\MedicalReport;


class MedicalReportController extends Controller
{
    //
    use ApiResponseTrait;
    public function index(){
        // $posts=Post::get();



        $MedicalReports=MedicalReportResource::collection(MedicalReport::get());
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
        return $this->ApiResponse($MedicalReports,"all done",200);

    }

    public function show($id){

        // $post=Post::find($id);

        $MedicalReport= MedicalReport::find($id);

        if($MedicalReport){
            return $this->ApiResponse(new MedicalReportResource($MedicalReport),"all done",200);
        }
        return $this->ApiResponse(null,"MedicalReport not found",404);

    }

    //id name  percentageofmodel  percentageofdoctor  Hospital_id  patient_id


    public function store(REQUEST $request){
        //validation
        $validator=Validator::make($request->all(),[
            'Obstructive_HCM'=>'required',//
            'Gender'=>'required',
            'Syncope'=>'required',
            'Dyspnea'=>'required',
            'Fatigue'=>'required',
            'Presyncope'=>'required',
            'NYHA_Class'=>'required',
            'Atrial_Fibrillation'=>'required',
            'Hypertension'=>'required',
            'Beta_blocker'=>'required',
            'Ca_Channel_Blockers'=>'required',
            'ACEI_ARB'=>'required',
            'Coumadin'=>'required',
            'Max_Wall_Thick'=>'required',
            'Septal_Anterior_Motion'=>'required',
            'Ejection_Fraction'=>'required',
            'Hospital_id'=>'required',
            'patient_id'=>'required'

        ]);



        if($validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }


        $MedicalReports=MedicalReport::create($request->all());
        if($MedicalReports){
            return $this->ApiResponse(new MedicalReportResource($MedicalReports),"MedicalReport created successfully",201);
        }
         return $this->ApiResponse(null,"MedicalReport not saved",400);

    }

    public function update(REQUEST $request,$id){
           //validation
           $validator=Validator::make($request->all(),[
            'Obstructive_HCM'=>'required',//
            'Gender'=>'required',
            'Syncope'=>'required',
            'Dyspnea'=>'required',
            'Fatigue'=>'required',
            'Presyncope'=>'required',
            'NYHA_Class'=>'required',
            'Atrial_Fibrillation'=>'required',
            'Hypertension'=>'required',
            'Beta_blocker'=>'required',
            'Ca_Channel_Blockers'=>'required',
            'ACEI_ARB'=>'required',
            'Coumadin'=>'required',
            'Max_Wall_Thick'=>'required',
            'Septal_Anterior_Motion'=>'required',
            'Ejection_Fraction'=>'required',
            'Hospital_id'=>'required'
         ]);
         if($validator->fails()){
             return $this->ApiResponse(null,$validator->errors(),400);
         }
         $MedicalReport=MedicalReport::find($id);
         if(!$MedicalReport){
            return $this->ApiResponse(null,"MedicalReport not found",400);
        }

        $MedicalReport->update($request->all());
         if($MedicalReport){
            return $this->ApiResponse(new MedicalReportResource($MedicalReport),"MedicalReport updated successfully",201);

         }
    }

    public function destroy($id){
        $MedicalReport=MedicalReport::find($id);
        if(!$MedicalReport){
            return $this->ApiResponse(null, "MedicalReport not found",404);
        }
        $MedicalReport->delete($id);

        if($MedicalReport){
            return $this->ApiResponse(new MedicalReportResource($MedicalReport),"MedicalReport deleted successfully",200);
        }
    }
}
