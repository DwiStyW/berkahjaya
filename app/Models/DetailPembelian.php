<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;
    protected $table="detail_pembelian";
    protected $fillable=[
        'tanggal',
        'kode_pembelian',
        'supplier',
        'id_master_mentah',
        'id_model',
        'status',
        'jumlah',
        'vol',
        'harga_model',
        'total_harga',
    ];
}