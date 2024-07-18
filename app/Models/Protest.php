<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protest extends Model
{
    use HasFactory;
    protected $table="test";
    protected $fillable=[
        'kode_produksi',
        'tanggal',
        'id_supplier',
        'supplier',
        'log_opc',
        'harga_log',
        'id_opc',
        'id_opcb',
        'id_ppc',
        'id_mk',
        'id_ampulur',
    ];
}