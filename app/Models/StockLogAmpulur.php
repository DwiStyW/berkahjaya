<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLogAmpulur extends Model
{
    use HasFactory;
    protected $table="stock_ampulur";
    protected $fillable=[
        'kode',
        'tanggal',
        'supplier',
        'volume',
        'harga',
        'harga_master',
        'ket',
    ];
}