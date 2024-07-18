<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMasterMentah extends Model
{
    use HasFactory;
    protected $table="detail_master_mentah";
    protected $fillable=[
        'id_master_mentah',
        'kelas_model',
        'model',
        'pakem',
        'pakem_pembulatan',
        'harga',
    ];
}