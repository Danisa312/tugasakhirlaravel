<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
   public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('nama_perusahaan', 255)->nullable();
        $table->text('alamat')->nullable();
        $table->string('logo_path', 255)->nullable();
        $table->string('kontak', 50)->nullable();
        $table->string('email_perusahaan', 100)->nullable();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
