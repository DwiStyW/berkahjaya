<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporary extends Model
{
    use HasFactory;
    protected $table="temporary";
    protected $fillable=[
        'kode_produksi',
        'tanggal',
        'id_supplier',
        'supplier',
        'log_opc',
        'harga_log',
        'id_produk',
        'pcs',
        'ukuran',
        'harga',
        'total',
        'size1',
        'size2',
        'size3',
    ];
}