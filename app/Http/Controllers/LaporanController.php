<?php

namespace App\Http\Controllers;

use App\Models\LogOpc;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(){


        return view('laporan.laporan');
    }
    public function cetak(Request $request){
        $tgl_awal=$request->tanggal_awal;
        $newDateAwal = date("Y-m-d", strtotime($tgl_awal));
        $tgl_akhir=$request->tanggal_akhir;
        $newDateAkhir = date("Y-m-d", strtotime($tgl_akhir));

        $laporan=DB::select("SELECT tanggal,supplier,vol as vol_masuk,0 as vol_keluar,total_harga as harga_masuk,0 as harga_keluar from pembelian  where tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'
                            UNION ALL
                            SELECT tanggal,supplier,0 as vol_masuk,vol_m3 as vol_keluar,0 as harga_masuk,total_harga as harga_keluar from penjualan where tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir' ORDER BY tanggal asc");

        // dump($laporan);
        // masuk keluar
        $pembelian=DB::select("SELECT * from pembelian where tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'");
        $penjualanOpc=DB::select("SELECT * from penjualan where (tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir') and id_master='1' order by tanggal asc");
        $penjualanLimbah=DB::select("SELECT * from penjualan where (tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir') and id_master!='1'");

        // stock log
        $stockSengon=DB::select("SELECT * from stock_log_masuk where tanggal <= '$newDateAkhir'");
        $stockKeras=DB::select("SELECT * from stock_log_masuk_keras where tanggal <= '$newDateAkhir'");

        // stock jadi
        $stockOpc=DB::select("SELECT * from stock_opc where tanggal <= '$newDateAkhir'");
        $stockPpc=DB::select("SELECT * from stock_ppc where tanggal <= '$newDateAkhir'");
        $stockMk=DB::select("SELECT * from stock_mk where tanggal <= '$newDateAkhir'");

        // operasional
        $operasional=DB::select("SELECT * from operasional where tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'");

        // produksi
        $produksi=DB::select("SELECT * from produksi where  tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'");
        $produksiGroup=DB::select("SELECT tanggal,kode_produksi,log_opc,harga_log from produksi where  tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir' group by tanggal,kode_produksi,log_opc,harga_log");

        // hitung total beli
        $arr_beliVol=[];
        $arr_beliHarga=[];
        foreach($pembelian as $beli){
            array_push($arr_beliVol,$beli->vol);
            array_push($arr_beliHarga,$beli->total_harga);
        }
        $total_beliVol=array_sum($arr_beliVol);
        $total_beliHarga=array_sum($arr_beliHarga);

        // hitung jual opc
        $arr_jualOpcVol=[];
        $arr_jualOpcHarga=[];
        foreach($penjualanOpc as $jualOpc){
            array_push($arr_jualOpcVol,$jualOpc->vol_m3);
            array_push($arr_jualOpcHarga,$jualOpc->total_harga);
        }
        $total_jualOpcVol=array_sum($arr_jualOpcVol);
        $total_jualOpcHarga=array_sum($arr_jualOpcHarga);

        // hitung jual limbah
        $arr_jualLimbahVol=[];
        $arr_jualLimbahHarga=[];
        foreach($penjualanLimbah as $jualLimbah){
            array_push($arr_jualLimbahVol,$jualLimbah->vol_m3);
            array_push($arr_jualLimbahHarga,$jualLimbah->total_harga);
        }
        $total_jualLimbahVol=array_sum($arr_jualLimbahVol);
        $total_jualLimbahHarga=array_sum($arr_jualLimbahHarga);

        // stock awal opc
        $arr_soVolMasuk=[];
        $arr_soVolKeluar=[];
        $arr_soHargaMasuk=[];
        $arr_soHargaKeluar=[];
        foreach($stockOpc as $so){
            if($so->ket=='masuk'){
                array_push($arr_soVolMasuk,$so->volume);
                array_push($arr_soHargaMasuk,$so->harga);
            }
           if($so->ket=='keluar'){
                array_push($arr_soVolKeluar,$so->volume);
                array_push($arr_soHargaKeluar,$so->volume * $so->harga_master);
           }
        }
        $total_soVolMasuk=array_sum($arr_soVolMasuk);
        $total_soVolKeluar=array_sum($arr_soVolKeluar);
        $total_soHargaMasuk=array_sum($arr_soHargaMasuk);
        $total_soHargaKeluar=array_sum($arr_soHargaKeluar);

        $total_soVol=$total_soVolMasuk-$total_soVolKeluar;
        $total_soHarga=$total_soHargaMasuk-$total_soHargaKeluar;

        // stock ppc
        $arr_spVolMasuk=[];
        $arr_spVolKeluar=[];
        $arr_spHargaMasuk=[];
        $arr_spHargaKeluar=[];
        foreach($stockPpc as $sp){
            if($sp->ket=='masuk'){
                array_push($arr_spVolMasuk,$sp->volume);
                array_push($arr_spHargaMasuk,$sp->harga);
            }
           if($sp->ket=='keluar'){
                array_push($arr_spVolKeluar,$sp->volume);
                array_push($arr_spHargaKeluar,$sp->volume * $sp->harga_master);
           }
        }
        $total_spVolMasuk=array_sum($arr_spVolMasuk);
        $total_spVolKeluar=array_sum($arr_spVolKeluar);
        $total_spHargaMasuk=array_sum($arr_spHargaMasuk);
        $total_spHargaKeluar=array_sum($arr_spHargaKeluar);

        $total_spVol=$total_spVolMasuk-$total_spVolKeluar;
        $total_spHarga=$total_spHargaMasuk-$total_spHargaKeluar;

        // stock mk
        $arr_smVolMasuk=[];
        $arr_smVolKeluar=[];
        $arr_smHargaMasuk=[];
        $arr_smHargaKeluar=[];
        foreach($stockMk as $sm){
            if($sm->ket=='masuk'){
                array_push($arr_smVolMasuk,$sm->volume);
                array_push($arr_smHargaMasuk,$sm->harga);
            }
           if($sm->ket=='keluar'){
                array_push($arr_smVolKeluar,$sm->volume);
                array_push($arr_smHargaKeluar,$sm->volume * $sm->harga_master);
           }
        }
        $total_smVolMasuk=array_sum($arr_smVolMasuk);
        $total_smVolKeluar=array_sum($arr_smVolKeluar);
        $total_smHargaMasuk=array_sum($arr_smHargaMasuk);
        $total_smHargaKeluar=array_sum($arr_smHargaKeluar);

        $total_smVol=$total_smVolMasuk-$total_smVolKeluar;
        $total_smHarga=$total_smHargaMasuk-$total_smHargaKeluar;

        // stock log
        // sengon
        $arr_ssVolMasuk=[];
        $arr_ssVolKeluar=[];
        $arr_ssHargaMasuk=[];
        $arr_ssHargaKeluar=[];
        foreach($stockSengon as $ss){
            if($ss->ket=='masuk'){
                array_push($arr_ssVolMasuk,$ss->volume);
                array_push($arr_ssHargaMasuk,$ss->harga);
            }
           if($ss->ket=='keluar'){
                array_push($arr_ssVolKeluar,$ss->volume);
                array_push($arr_ssHargaKeluar,$ss->harga);
           }
        }
        $total_ssVolMasuk=array_sum($arr_ssVolMasuk);
        $total_ssVolKeluar=array_sum($arr_ssVolKeluar);
        $total_ssHargaMasuk=array_sum($arr_ssHargaMasuk);
        $total_ssHargaKeluar=array_sum($arr_ssHargaKeluar);

        $total_ssVol=$total_ssVolMasuk-$total_ssVolKeluar;
        $total_ssHarga=$total_ssHargaMasuk-$total_ssHargaKeluar;
        // keras
        $arr_skVolMasuk=[];
        $arr_skVolKeluar=[];
        $arr_skHargaMasuk=[];
        $arr_skHargaKeluar=[];
        foreach($stockKeras as $sk){
            if($sk->ket=='masuk'){
                array_push($arr_skVolMasuk,$sk->volume);
                array_push($arr_skHargaMasuk,$sk->harga);
            }
           if($sk->ket=='keluar'){
                array_push($arr_skVolKeluar,$sk->volume);
                array_push($arr_skHargaKeluar,$sk->harga);
           }
        }
        $total_skVolMasuk=array_sum($arr_skVolMasuk);
        $total_skVolKeluar=array_sum($arr_skVolKeluar);
        $total_skHargaMasuk=array_sum($arr_skHargaMasuk);
        $total_skHargaKeluar=array_sum($arr_skHargaKeluar);

        $total_skVol=$total_skVolMasuk-$total_skVolKeluar;
        $total_skHarga=$total_skHargaMasuk-$total_skHargaKeluar;
        // total
        $total_slVol=$total_ssVol+$total_skVol;
        $total_slHarga=$total_ssHarga+$total_skHarga;
        // dd($total_skHarga);

        // hitung beban operasional
        $arr_opHarga=[];
        foreach($operasional as $oper){
            array_push($arr_opHarga,$oper->harga);
        }
        $total_operasional=array_sum($arr_opHarga);

                // hitung beban operasional
        $arr_proVol=[];
        $arr_proHarga=[];
        foreach($produksiGroup as $pro){
            array_push($arr_proVol,$pro->log_opc);
            array_push($arr_proHarga,$pro->harga_log);
        }
        $total_proVol=array_sum($arr_proVol);
        $total_proHarga=array_sum($arr_proHarga);
        return view('laporan.tampil-laporan',compact(
            'tgl_awal','tgl_akhir','newDateAwal','newDateAkhir','total_beliVol','total_beliHarga','penjualanOpc',
            'total_jualOpcVol','total_jualOpcHarga','total_soVol','total_soHarga','total_spVol','total_spHarga','total_smVol','total_smHarga',
            'total_slVol','total_slHarga','penjualanLimbah','total_jualLimbahHarga','operasional','total_operasional',
            'total_proVol','total_proHarga'));
    }

    public function produksi($newDateAwal,$newDateAkhir){
        $produksi=DB::select("SELECT * from produksi where  tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'");
        $produksiGroup=DB::select("SELECT tanggal,supplier,kode_produksi,log_opc,harga_log,
                                sum(opc_m3) as sum_opc_m3,sum(opc_total) as sum_opc_total,sum(opcb_total) as sum_opcb_total,sum(ppc_total) as sum_ppc_total,sum(ampulur_total) as sum_ampulur_total,
                                count(*) as count from produksi where  tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'
                                group by tanggal,supplier,kode_produksi,log_opc,harga_log order by tanggal asc");
        // dd($produksiGroup);
        $arr_proVol=[];
        $arr_proHarga=[];
        foreach($produksiGroup as $pro){
            array_push($arr_proVol,$pro->log_opc);
            array_push($arr_proHarga,$pro->harga_log);
        }
        $total_proVol=array_sum($arr_proVol);
        $total_proHarga=array_sum($arr_proHarga);

        return view('laporan.tampil-laporan-produksi',compact('newDateAwal','newDateAkhir','produksi','produksiGroup'));
    }

    public function flow($newDateAwal,$newDateAkhir){
        $produksiGroup=DB::select("SELECT tanggal,id_supplier,kode_produksi,log_opc,harga_log,
                        sum(opc_m3) as sum_opc_m3,sum(opc_total) as sum_opc_total,sum(opcb_total) as sum_opcb_total,sum(ppc_total) as sum_ppc_total,sum(ampulur_total) as sum_ampulur_total,
                        count(*) as count from produksi where  tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'
                        group by tanggal,id_supplier,kode_produksi,log_opc,harga_log order by tanggal asc");

        $id_sup=[];
        foreach($produksiGroup as $pg){
            array_push($id_sup,$pg->id_supplier);
        }
        $id_supplierStr=implode(",",$id_sup);
        $id_supplierArr=explode(",",$id_supplierStr);

        $log=LogOpc::whereIn('id',$id_supplierArr)->orderby('tanggal','asc')->get();
        $laporan=DB::select("SELECT tanggal,supplier,uraian as vol_masuk,null as vol_keluar,harga as harga_masuk,null as harga_keluar from log_opc  where  log_opc.id in ($id_supplierStr)
                            UNION ALL
                            SELECT tanggal,supplier,null as vol_masuk,vol_m3 as vol_keluar,null as harga_masuk,total_harga as harga_keluar from penjualan where tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir' and id_master='1' ORDER BY tanggal asc");

        $stockOpc=DB::select("SELECT * from stock_opc where tanggal <= '$newDateAkhir'");
        $penjualanOpc=DB::select("SELECT * from penjualan where (tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir') and id_master='1' order by tanggal asc");

                // stock awal opc
        $arr_soVolMasuk=[];
        $arr_soVolKeluar=[];
        $arr_soHargaMasuk=[];
        $arr_soHargaKeluar=[];
        foreach($stockOpc as $so){
            if($so->ket=='masuk'){
                array_push($arr_soVolMasuk,$so->volume);
                array_push($arr_soHargaMasuk,$so->harga);
            }
           if($so->ket=='keluar'){
                array_push($arr_soVolKeluar,$so->volume);
                array_push($arr_soHargaKeluar,$so->volume * 1450000);
           }
        }
        $total_soVolMasuk=array_sum($arr_soVolMasuk);
        $total_soVolKeluar=array_sum($arr_soVolKeluar);
        $total_soHargaMasuk=array_sum($arr_soHargaMasuk);
        $total_soHargaKeluar=array_sum($arr_soHargaKeluar);

        $total_soVol=$total_soVolMasuk-$total_soVolKeluar;
        $total_soHarga=$total_soHargaMasuk-$total_soHargaKeluar;

         // hitung jual opc
        $arr_jualOpcVol=[];
        $arr_jualOpcHarga=[];
        foreach($penjualanOpc as $jualOpc){
            array_push($arr_jualOpcVol,$jualOpc->vol_m3);
            array_push($arr_jualOpcHarga,$jualOpc->vol_m3*1450000);
        }
        $total_jualOpcVol=array_sum($arr_jualOpcVol);
        $total_jualOpcHarga=array_sum($arr_jualOpcHarga);

        // hitung log
        $arr_logVol=[];
        $arr_logHarga=[];
        foreach($log as $l){
            array_push($arr_logVol,$l->uraian);
            array_push($arr_logHarga,$l->harga);
        }
        $total_logVol=array_sum($arr_logVol);
        $total_logHarga=array_sum($arr_logHarga);
// dd($laporan);
        return view('laporan.tampil-laporan-flow',compact('newDateAwal','newDateAkhir','laporan','total_soVol','total_soHarga','total_jualOpcVol','total_jualOpcHarga','total_logVol','total_logHarga'));

    }
    public function penjualan($newDateAwal,$newDateAkhir){
        $penjualanOpc=DB::select("SELECT * from penjualan where (tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir') and id_master='1' order by tanggal asc");
        $penjualanLimbah=DB::select("SELECT * from penjualan where (tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir') and id_master!='1'");

                // hitung jual opc
        $arr_jualOpcVol=[];
        $arr_jualOpcHarga=[];
        foreach($penjualanOpc as $jualOpc){
            array_push($arr_jualOpcVol,$jualOpc->vol_m3);
            array_push($arr_jualOpcHarga,$jualOpc->total_harga);
        }
        $total_jualOpcVol=array_sum($arr_jualOpcVol);
        $total_jualOpcHarga=array_sum($arr_jualOpcHarga);

        // hitung jual limbah
        $arr_jualLimbahVol=[];
        $arr_jualLimbahHarga=[];
        foreach($penjualanLimbah as $jualLimbah){
            array_push($arr_jualLimbahVol,$jualLimbah->vol_m3);
            array_push($arr_jualLimbahHarga,$jualLimbah->total_harga);
        }
        $total_jualLimbahVol=array_sum($arr_jualLimbahVol);
        $total_jualLimbahHarga=array_sum($arr_jualLimbahHarga);

        return view('laporan.tampil-laporan-penjualan',compact('newDateAwal','newDateAkhir','penjualanOpc','penjualanLimbah','total_jualOpcVol','total_jualOpcHarga','total_jualLimbahVol','total_jualLimbahHarga'));
    }

    public function pembelian($newDateAwal,$newDateAkhir){
        $pembelian=DB::select("SELECT * from pembelian where tanggal >= '$newDateAwal' and tanggal <= '$newDateAkhir'");
        // hitung total beli
        $arr_beliVol=[];
        $arr_beliHarga=[];
        foreach($pembelian as $beli){
            array_push($arr_beliVol,$beli->vol);
            array_push($arr_beliHarga,$beli->total_harga);
        }
        $total_beliVol=array_sum($arr_beliVol);
        $total_beliHarga=array_sum($arr_beliHarga);

        return view('laporan.tampil-laporan-pembelian',compact('newDateAwal','newDateAkhir','pembelian','total_beliVol','total_beliHarga'));
    }
}