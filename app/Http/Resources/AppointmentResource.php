<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            // 'xx'=>$this->id,
            'id'=>$this->id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'gender'=>$this->gender,
            'date'=>$this->date,
            'hospital_id'=>$this->hospital_id,
            'patient_id'=>$this->patient_id,
        ];
    }
}
// 'doctor_id'=>$this->doctor_id,