<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLogMasuk extends Model
{
    use HasFactory;
    protected $table="stock_log_masuk";
    protected $fillable=[
        'kode',
        'tanggal',
        'supplier',
        'volume',
        'harga',
        'ket',
    ];
}