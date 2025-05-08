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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyakit_id');
            $table->foreignId('gejala_id')->nullable();
            $table->float('bobot_cf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }

    // public function up()
    // {
    //     Schema::table('roles', function (Blueprint $table) {
    //         $table->dropColumn('kode_gejala');
    //     });
    // }

    // public function down()
    // {
    //     Schema::table('roles', function (Blueprint $table) {
    //         $table->string('kode_gejala')->nullable();
    //     });
    // }

};
