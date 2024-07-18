<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(){

        $startDate="2024-04-14";
        $endtDate="2024-04-20";

        $produksi_all=Produksi::where('tanggal','>=',$startDate)
            ->where('tanggal','<=',$endtDate)
            ->get();

        $total_opc=0;
        $total_opcb=0;
        $total_ppc=0;
        $total_mk=0;
        $total_ampulur=0;
        foreach($produksi_all as $proAll){
            $total_opc+=$proAll->opc_m3;
            $total_opcb+=$proAll->opcb_m3;
            $total_ppc+=$proAll->ppc_m;
            $total_mk+=$proAll->mk_m;
            $total_ampulur+=$proAll->ampulur_pcs;

        }
        $produksi_perkode=Produksi::where('tanggal','>=',$startDate)
            ->where('tanggal','<=',$endtDate)
            ->groupby('kode_produksi','id_supplier','log_opc','harga_log')
            ->select('kode_produksi','id_supplier','log_opc','harga_log')
            ->get();


        dump($total_opc,$total_opcb,$total_ppc,$total_mk,$total_ampulur);
    }
}