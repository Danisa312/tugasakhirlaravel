<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanBulananTable extends Migration
{
    public function up()
    {
        Schema::create('laporan_bulanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan'); // 1–12
            $table->unsignedSmallInteger('tahun');
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['bulan', 'tahun']); // hanya satu laporan per bulan & tahun
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_bulanan');
    }
}
