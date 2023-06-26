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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('gender');
            $table->bigInteger('Hospital_id')->unsigned()->defualt(1);
            $table->foreign('Hospital_id')->references('id')->on('hospitals')->onDelete('cascade');

            $table->bigInteger('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->time('date');
              // $table->bigInteger('doctor_id')->unsigned();
            // $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->timestamps();
        });


        //name   id  phone patient_id doctor_id  hospital_id gender


        //  Schema::create('appointments', function (Blueprint $table) {

        //                 $table->id();

        //                 $table->time('date');

        //                 $table->bigInteger('Hospital_id')->unsigned();
        //                 $table->foreign('Hospital_id')->references('id')->on('hospitals')->onDelete('cascade');




        //                 $table->bigInteger('patient_id')->unsigned();





        //                 $table->bigInteger('doctor_id')->unsigned();

        //                 $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

        //                 $table->timestamps();

        //   });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};