<?php

namespace App\Http\Controllers;

use App\Models\StockLogAmpulur;
use App\Models\StockLogMasuk;
use App\Models\StockLogMasukKeras;
use App\Models\StockLogMasukKeras260;
use App\Models\StockLogMasukSengon260;
use App\Models\StockLogMk;
use App\Models\StockLogOpc;
use App\Models\StockLogPpc;
use Illuminate\Http\Request;

class StockController extends Controller
{
    //
    public function stock_baku(){
        $logmasuk=StockLogMasuk::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $logmasukkeras=StockLogMasukKeras::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $logsengon260=StockLogMasukSengon260::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $logkeras260=StockLogMasukKeras260::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $stockopc=StockLogOpc::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $stockppc=StockLogPpc::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $stockmk=StockLogMk::orderby('tanggal','asc')->orderby('ket','desc')->get();
        $stockampulur=StockLogAmpulur::orderby('tanggal','asc')->orderby('ket','desc')->get();
        // dd($logmasuk);
        return view('stock.list-stock-baku-global',compact('logmasuk','logmasukkeras','logsengon260','logkeras260','stockopc','stockppc','stockmk','stockampulur'));
    }
    public function stock_masuk(){
        // $stock=StockLogMasuk::orderby('tanggal','asc')->get();
        $data=StockLogMasuk::orderby('tanggal','asc')->orderby('ket','desc')->get();
        foreach($data as $d){
            if($d->ket=='masuk' && $d->status!=null){
                $kode=$d->status;
                $md5 = md5( $kode );
                $md5 = preg_replace( '/[^0-9a-fA-F]/', '', $md5 );
                $color = substr( $md5, 0, 6 );
                $hex = str_split( $color, 1 );
                $rgbd = array_map( 'hexdec', $hex );
                $rgba = array(
                    ( $rgbd[0] * $rgbd[1] ),
                    ( $rgbd[2] * $rgbd[3] ),
                    ( $rgbd[4] * $rgbd[5] ),
                );
                $rgb_text=implode(",",$rgba);
                $rgb='rgb('.$rgb_text.',0.4)';
            }elseif($d->ket=='keluar'){
                $kode=$d->kode;
                $md5 = md5( $kode );
                $md5 = preg_replace( '/[^0-9a-fA-F]/', '', $md5 );
                $color = substr( $md5, 0, 6 );
                $hex = str_split( $color, 1 );
                $rgbd = array_map( 'hexdec', $hex );
                $rgba = array(
                    ( $rgbd[0] * $rgbd[1] ),
                    ( $rgbd[2] * $rgbd[3] ),
                    ( $rgbd[4] * $rgbd[5] ),
                );
                $rgb_text=implode(",",$rgba);
                $rgb='rgb('.$rgb_text.',0.4)';
            }
            elseif($d->ket=='masuk' && $d->status==null){
                $rgb='#fff';
            }


            // dump($cari);
            // dd($cari);
            $stock[]=[
                'kode'=>$d->kode,
                'tanggal'=>$d->tanggal,
                'supplier'=>$d->supplier,
                'volume'=>$d->volume,
                'harga'=>$d->harga,
                'ket'=>$d->ket,
                'status'=>$d->status,
                'color'=>$rgb,
            ];
        }
        // dd($stock);
        return view('stock.list-stock-masuk',compact('stock'));
    }

    public function stock_masuk_keras(){
        $data=StockLogMasukKeras::orderby('tanggal','asc')->orderby('ket','desc')->get();
        foreach($data as $d){
            if($d->ket=='masuk' && $d->status!=null){
                $kode=$d->status;
                $md5 = md5( $kode );
                $md5 = preg_replace( '/[^0-9a-fA-F]/', '', $md5 );
                $color = substr( $md5, 0, 6 );
                $hex = str_split( $color, 1 );
                $rgbd = array_map( 'hexdec', $hex );
                $rgba = array(
                    ( $rgbd[0] * $rgbd[1] ),
                    ( $rgbd[2] * $rgbd[3] ),
                    ( $rgbd[4] * $rgbd[5] ),
                );
                $rgb_text=implode(",",$rgba);
                $rgb='rgb('.$rgb_text.',0.4)';
            }elseif($d->ket=='keluar'){
                $kode=$d->kode;
                $md5 = md5( $kode );
                $md5 = preg_replace( '/[^0-9a-fA-F]/', '', $md5 );
                $color = substr( $md5, 0, 6 );
                $hex = str_split( $color, 1 );
                $rgbd = array_map( 'hexdec', $hex );
                $rgba = array(
                    ( $rgbd[0] * $rgbd[1] ),
                    ( $rgbd[2] * $rgbd[3] ),
                    ( $rgbd[4] * $rgbd[5] ),
                );
                $rgb_text=implode(",",$rgba);
                $rgb='rgb('.$rgb_text.',0.4)';
            }
            elseif($d->ket=='masuk' && $d->status==null){
                $rgb='#fff';
            }


            // dump($cari);
            // dd($cari);
            $stock[]=[
                'kode'=>$d->kode,
                'tanggal'=>$d->tanggal,
                'supplier'=>$d->supplier,
                'volume'=>$d->volume,
                'harga'=>$d->harga,
                'ket'=>$d->ket,
                'status'=>$d->status,
                'color'=>$rgb,
            ];
        }
        // dd($saldo_akhir);
        return view('stock.list-stock-masuk-keras',compact('stock'));
    }

    public function stock_masuk_sengon_260(){
        $stock=StockLogMasukSengon260::orderby('tanggal','asc')->get();
        // dd($saldo_akhir);
        return view('stock.list-stock-sengon-260',compact('stock'));
    }

    public function stock_masuk_keras_260(){
        $stock=StockLogMasukKeras260::orderby('tanggal','asc')->get();
        // dd($saldo_akhir);
        return view('stock.list-stock-keras-260',compact('stock'));
    }

    public function stock_opc(){
        $stock=StockLogOpc::orderby('tanggal','asc')->orderby('ket','desc')->get();
        return view('stock.list-stock-jadi-opc',compact('stock'));
    }
    public function stock_ppc(){
        $stock=StockLogPpc::orderby('tanggal','asc')->orderby('ket','desc')->get();
        return view('stock.list-stock-jadi-ppc',compact('stock'));
    }
    public function stock_mk(){
        $stock=StockLogMk::orderby('tanggal','asc')->orderby('ket','desc')->get();
        return view('stock.list-stock-jadi-mk',compact('stock'));
    }
    public function stock_ampulur(){
        $stock=StockLogAmpulur::orderby('tanggal','asc')->orderby('ket','desc')->get();
        return view('stock.list-stock-jadi-ampulur',compact('stock'));
    }

    public function getStock(Request $request){
        if($request->id==1){
            $stock=StockLogOpc::get();
        }else if($request->id==3){
            $stock=StockLogPpc::get();
        }else if($request->id==4){
            $stock=StockLogMk::get();
        }else if($request->id==5){
            $stock=StockLogAmpulur::get();
        }else{
            $stock=[];
        }
        return $stock;
    }
}
