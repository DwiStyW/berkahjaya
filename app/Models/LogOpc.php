<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOpc extends Model
{
    use HasFactory;
    protected $table="log_opc";
    protected $fillable=[
        'kode',
        'tanggal',
        'supplier',
        'uraian',
        'sengon',
        'harga_sengon',
        'stat_sengon',
        'keras',
        'harga_keras',
        'stat_keras',
        'harga',
        'ket',
        'status',
    ];
}