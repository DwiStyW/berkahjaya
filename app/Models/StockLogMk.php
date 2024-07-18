<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLogMk extends Model
{
    use HasFactory;
    protected $table="stock_mk";
    protected $fillable=[
        'kode',
        'tanggal',
        'supplier',
        'volume',
        'harga',
        'ket',
    ];
}