<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table="pembelian";
    protected $fillable=[
        'tanggal',
        'kode_pembelian',
        'supplier',
        'jumlah_truk',
        'no_truk',
        'vol',
        'total_harga',
    ];
}