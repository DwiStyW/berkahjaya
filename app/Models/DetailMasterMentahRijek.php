<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMasterMentahRijek extends Model
{
    use HasFactory;
    protected $table="detail_master_mentah_rijek";
    protected $fillable=[
        'id_master_mentah',
        'kelas_model',
        'model',
        'panjang',
        'pakem',
        'harga',
        'harga_khusus',
    ];
}
