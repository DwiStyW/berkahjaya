<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLogMasukSengon260 extends Model
{
    use HasFactory;
    protected $table="stock_log_masuk_sengon260";
    protected $fillable=[
        'kode',
        'tanggal',
        'supplier',
        'volume',
        'harga',
        'ket',
    ];
}