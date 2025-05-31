<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanBulanan extends Model
{
    use HasFactory;

    protected $table = 'laporan_bulanan';

    protected $fillable = [
        'bulan',
        'tahun',
        'total_pendapatan',
        'total_pengeluaran',
        'saldo_akhir',
        'catatan',
    ];

    protected $casts = [
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];
}
