<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilProduk extends Model
{
    use HasFactory;
    protected $table="hasil_produksi";
    protected $fillable=[
        'hasil_produksi',
        'satuan',
        'harga',
    ];
}