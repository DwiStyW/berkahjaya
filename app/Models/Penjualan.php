<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table="penjualan";
    protected $fillable=[
        'kode_penjualan',
        'tanggal',
        'supplier',
        'alamat',
        'grade',
        'jenis_kayu',
        'ukuran1',
        'ukuran2',
        'ukuran3',
        'vol_m3',
        'pcs',
        'crate',
        'harga_vol_m3',
        'total_harga',
    ];
}