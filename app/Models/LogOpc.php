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
        'harga',
        'ket',
        'status',
    ];
}