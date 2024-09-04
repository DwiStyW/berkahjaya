<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMentah extends Model
{
    use HasFactory;
    protected $table="master_mentah";
    protected $fillable=[
        'jenis_muatan',
        'jenis_kayu',
        'rumus_a',
        'rumus_b',
        'rumus_c',
        'status',
    ];
}