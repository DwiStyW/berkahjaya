<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;
    protected $table="produksi";
    protected $fillable=[
        'kode_produksi',
        'tanggal',
        'id_supplier',
        'supplier',
        'persentase',
        'log_opc',
        'harga_log',
        'opc_pcs',
        'opc_m3',
        'opc_harga',
        'opc_total',
        'opcb_pcs',
        'opcb_m3',
        'opcb_harga',
        'opcb_total',
        'ppc_m',
        'ppc_harga',
        'ppc_total',
        'mk_m',
        'mk_harga',
        'mk_total',
        'ampulur_pcs',
        'ampulur_total',
    ];
}