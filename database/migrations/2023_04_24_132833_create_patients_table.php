<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('gender');
            $table->boolean('is_patient')->default(true);//is_admin,is_doctor,is_patient
            // $table->bigInteger('Hospital_id')->unsigned();//
            // $table->foreign('Hospital_id')->references('id')->on('hospitals');
            $table->rememberToken();
            $table->timestamps();
        });
            // MedicalRecord
            // $table->bigInteger('MedicalRecord_id')->unsigned();
            // $table->foreign('MedicalRecord_id')->references('id')->on('medical_records')->onDelete('cascade');
            // $table->bigInteger('MedicalRecord_id')->unsigned();
            // $table->foreign('MedicalRecord_id')->references('id')->on('medical_records')->onDelete('cascade');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};