<?php

namespace App\Http\Controllers;

use App\Models\LogOpc;
use App\Models\Produksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $startOfWeek = date("Y-m-d", strtotime("sunday last week"));
        $endOfWeek = date("Y-m-d", strtotime("saturday this week"));
        $startlastOfWeek = date("Y-m-d", strtotime("sunday 1 weeks ago"));
        $endlastOfWeek = date("Y-m-d", strtotime("Saturday last week"));
        $today=date("Y-m-d");
        // total uraian perminggu
        $cari_logopc = LogOpc::where('tanggal','>=',$startOfWeek)
            ->where('tanggal','<=',$today)
            ->get();

        $total_uraian_debet=0;
        $total_uraian_kredit=0;
        foreach($cari_logopc as $cl){
            if($cl->ket=='beli'){
                $total_uraian_kredit+=$cl->uraian;
            }else{
                $total_uraian_debet+=$cl->uraian;
            }
        }

        // produksi minngu ini
        $produksi_perkode=Produksi::where('tanggal','>=',$startOfWeek)
            ->where('tanggal','<=',$today)
            ->groupby('kode_produksi','id_supplier','log_opc','harga_log')
            ->select('kode_produksi','id_supplier','log_opc','harga_log')
            ->get();
        $produksi=Produksi::where('tanggal','>=',$startOfWeek)
            ->where('tanggal','<=',$today)
            ->get();
        $total_uraian_logopc=0;
        $total_uraian_proopc=0;

        $total_harga_logopc=0;
        $total_harga_Opc=0;
        $total_harga_OpcB=0;
        $total_harga_Ppc=0;
        $total_harga_Mk=0;
        $total_harga_Ampulur=0;

        $persentThisweek=0;

        foreach($produksi_perkode as $pp){
            $total_uraian_logopc+=$pp->log_opc;
            $total_harga_logopc+=$pp->harga_log;
        }
        foreach($produksi as $p){
            $total_uraian_proopc+=$p->opc_m3;
            $total_harga_Opc+=$p->opc_total;
            $total_harga_OpcB+=$p->opcb_total;
            $total_harga_Ppc+=$p->ppc_total;
            $total_harga_Mk+=$p->mk_total;
            $total_harga_Ampulur+=$p->ampulur_total;
        }
        $total_harga_produksi=$total_harga_Opc+$total_harga_OpcB+$total_harga_Ppc+$total_harga_Ampulur;
        $total_selisih_produksi=$total_harga_produksi-$total_harga_logopc;
        $total_selisih_opc=$total_harga_Opc-$total_harga_logopc;
        $total_pendapatan=$total_selisih_produksi-$total_selisih_opc;

        if(count($produksi)>0){
            $persentThisweek=$total_uraian_proopc/$total_uraian_logopc*100;
        }


        // produksi minggu lalu
        $produksiLastweek_perkode=Produksi::where('tanggal','>=',$startlastOfWeek)
            ->where('tanggal','<=',$endlastOfWeek)
            ->groupby('kode_produksi','id_supplier','log_opc','harga_log')
            ->select('kode_produksi','id_supplier','log_opc','harga_log')
            ->get();
        $produksiLastweek=Produksi::where('tanggal','>=',$startlastOfWeek)
            ->where('tanggal','<=',$endlastOfWeek)
            ->get();
        $total_uraian_logopc_lastweek=0;
        $total_uraian_proopc_lastweek=0;

        $total_harga_logopc_lastweek=0;
        $total_harga_Opc_lastweek=0;
        $total_harga_OpcB_lastweek=0;
        $total_harga_Ppc_lastweek=0;
        $total_harga_Mk_lastweek=0;
        $total_harga_Ampulur_lastweek=0;

        $persentLastweek=0;
        foreach($produksiLastweek_perkode as $plp){
            $total_uraian_logopc_lastweek+=$plp->log_opc;
            $total_harga_logopc_lastweek+=$plp->harga_log;
        }
        foreach($produksiLastweek as $pl){
            $total_uraian_proopc_lastweek+=$pl->opc_m3;
            $total_harga_Opc_lastweek+=$pl->opc_total;
            $total_harga_OpcB_lastweek+=$pl->opcb_total;
            $total_harga_Ppc_lastweek+=$pl->ppc_total;
            $total_harga_Mk_lastweek+=$pl->mk_total;
            $total_harga_Ampulur_lastweek+=$pl->ampulur_total;
        }
        $total_harga_produksi_lastweek=$total_harga_Opc_lastweek+$total_harga_OpcB_lastweek+$total_harga_Ppc_lastweek+$total_harga_Ampulur_lastweek;
        $total_selisih_produksi_lastweek=$total_harga_produksi_lastweek-$total_harga_logopc_lastweek;
        $total_selisih_opc_lastweek=$total_harga_Opc_lastweek-$total_harga_logopc_lastweek;
        $total_pendapatan_lastweek=$total_selisih_produksi_lastweek-$total_selisih_opc_lastweek;

        if(count($produksiLastweek)>0){
            $persentLastweek=$total_uraian_proopc_lastweek/$total_uraian_logopc_lastweek*100;
        }


        // cari penjualan minggu ini
        $cari_logopc_jual_thisweek = LogOpc::where('tanggal','>=',$startOfWeek)
            ->where('tanggal','<=',$today)
            ->where('ket','jual')
            ->get();
        $delivery_thisweek=0;
        foreach($cari_logopc_jual_thisweek as $ljt){
            $delivery_thisweek+=$ljt->uraian;
        }
        $cari_logopc_jual_lastweek = LogOpc::where('tanggal','>=',$startlastOfWeek)
            ->where('tanggal','<=',$endlastOfWeek)
            ->where('ket','jual')
            ->get();
        $delivery_lastweek=0;
        foreach($cari_logopc_jual_lastweek as $ljl){
            $delivery_lastweek+=$ljl->uraian;
        }

        // perhitungan selisih persen
        // selisi penjualan
        $persen_deliv=0;
        if(count($cari_logopc_jual_lastweek)!=0 && count($cari_logopc_jual_lastweek)<0){
            if($delivery_thisweek > $delivery_lastweek){
                $ket_deliv='mdi-arrow-up-bold';
                $warna_deliv='text-success';
                $selisih_deliv=$delivery_thisweek-$delivery_lastweek;
                $persen_deliv=($selisih_deliv/$delivery_lastweek)*100;
            }else if($delivery_thisweek < $delivery_lastweek){

                $ket_deliv='mdi-arrow-down-bold';
                $warna_deliv='text-danger';
                $selisih_deliv=$delivery_thisweek-$delivery_lastweek;
                $persen_deliv=($selisih_deliv/$delivery_lastweek)*100;
            }else{
                $ket_deliv='mdi-minus';
                $warna_deliv='text-primary';
            }
        }else{
            $ket_deliv='mdi-arrow-up-bold';
                $warna_deliv='text-success';
                $selisih_deliv=$delivery_thisweek-$delivery_lastweek;
                $persen_deliv=100;
        }

        // selisih produksi
        $persen_pro=0;
        if(count($produksiLastweek)>0 && count($produksiLastweek)!=0){
            if($persentThisweek > $persentLastweek){
                $ket_pro='mdi-arrow-up-bold';
                $warna_pro='text-success';
                $selisih_pro=$persentThisweek - $persentLastweek;
                $persen_pro=($selisih_pro/$persentLastweek)*100;
            }else if($persentThisweek < $persentLastweek){

                $ket_pro='mdi-arrow-down-bold';
                $warna_pro='text-danger';
                $selisih_pro=$persentThisweek - $persentLastweek;
                $persen_pro=($selisih_pro/$persentLastweek)*100;
            }else{
                $ket_pro='mdi-minus';
                $warna_pro='text-primary';

            }
        }else{
            $ket_pro='mdi-arrow-up-bold';
                $warna_pro='text-success';
                $selisih_pro=$persentThisweek - 0;
                $persen_pro=100;
        }
        // persentasi penjualan
        if(count($cari_logopc_jual_thisweek)>0){
            $persentDeliv_Thisweek=$delivery_thisweek/$total_uraian_kredit*100;
        }else{
            $persentDeliv_Thisweek=0;
        }
        // perhitungan pendapatan

        $persen_pendapatan=0;
        if(count($produksiLastweek)>0 && count($produksiLastweek)!=0){
            if($total_pendapatan > $total_pendapatan_lastweek){
                $ket_pendapatan='mdi-arrow-up-bold';
                $warna_pendapatan='text-success';
                $selisih_pendapatan=$total_pendapatan - $total_pendapatan_lastweek;
                $persen_pendapatan=($selisih_pendapatan/$total_pendapatan_lastweek)*100;
            }else if($total_pendapatan < $total_pendapatan_lastweek){
                $ket_pendapatan='mdi-arrow-down-bold';
                $warna_pendapatan='text-danger';
                $selisih_pendapatan=$total_pendapatan - $total_pendapatan_lastweek;
                $persen_pendapatan=($selisih_pendapatan/$total_pendapatan_lastweek)*100;
            }else{
            $ket_pendapatan='mdi-minus';
            $warna_pendapatan='text-primary';
        }}
        else{
            $ket_pendapatan='mdi-arrow-up-bold';
                $warna_pendapatan='text-success';
                $selisih_pendapatan=$total_pendapatan - $total_pendapatan_lastweek;
                $persen_pendapatan=100;
        }


        $dataProduksi=[
            'persentThisweek'=>number_format($persentThisweek,2),
            'persen_pro'=>abs($persen_pro),
            'ket_pro'=>$ket_pro,
            'warna_pro'=>$warna_pro,
            'total_uraian_proopc'=>$total_uraian_proopc
        ];
        $dataPenjualan=[
            'persentDeliv_Thisweek'=>$persentDeliv_Thisweek,
            'persen_deliv'=>abs($persen_deliv),
            'ket_deliv'=>$ket_deliv,
            'warna_deliv'=>$warna_deliv,
            'delivery_thisweek'=>$delivery_thisweek
        ];
        $dataPendapatan=[
            'total_pendapatan'=>$total_pendapatan,
            'total_pendapatan_lastweek'=>$total_pendapatan_lastweek,
            'persen_pendapatan'=>abs($persen_pendapatan),
            'ket_pendapatan'=>$ket_pendapatan,
            'warna_pendapatan'=>$warna_pendapatan,
        ];
        // dump($dataPendapatan);
        // dd($dataProduksi);

        $ProduksiTable=Produksi::orderby('tanggal','DESC')->limit(6)->get();
        // dd($ProduksiTable);
        return view('dashboard.dashboard',compact('dataProduksi','dataPenjualan','dataPendapatan','ProduksiTable'));
    }


}