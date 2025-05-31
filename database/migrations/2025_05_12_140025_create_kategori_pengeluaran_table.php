<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriPengeluaranTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_pengeluaran', function (Blueprint $table) {
            $table->id(); // otomatis primary key dan auto-increment
            $table->string('nama', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_pengeluaran');
    }
}
