<?php

namespace App\Http\Controllers;

use App\Models\StockLogMasuk;
use App\Models\StockLogMasukKeras;
use App\Models\StockLogMk;
use App\Models\StockLogOpc;
use App\Models\StockLogPpc;
use Illuminate\Http\Request;

class StockController extends Controller
{
    //
    public function stock_baku(){
        $logmasuk=StockLogMasuk::get();
        $logmasukkeras=StockLogMasukKeras::get();
        $stockopc=StockLogOpc::get();
        $stockppc=StockLogPpc::get();
        $stockmk=StockLogMk::get();
        return view('stock.list-stock-baku-global',compact('logmasuk','logmasukkeras','stockopc','stockppc','stockmk'));
    }
    public function stock_masuk(){
        $stock=StockLogMasuk::get();
        $stockMasuk=StockLogMasuk::where('ket','masuk')->get();
        foreach($stockMasuk as $sM){}
        $stockKeluar=StockLogMasuk::where('ket','keluar')->get();
        foreach($stockKeluar as $sK){}

        $saldo_Vawal=0;
        $saldo_Vakhir=$stockMasuk->sum('volume')-$stockKeluar->sum('volume');
        $saldo_Hawal=0;
        $saldo_Hakhir=$stockMasuk->sum('harga')-$stockKeluar->sum('harga');
        // dd($saldo_akhir);
        return view('stock.list-stock-masuk',compact('stock'));
    }

    public function stock_masuk_keras(){
        $stock=StockLogMasukKeras::get();
        $stockMasuk=StockLogMasuk::where('ket','masuk')->get();
        foreach($stockMasuk as $sM){}
        $stockKeluar=StockLogMasuk::where('ket','keluar')->get();
        foreach($stockKeluar as $sK){}

        $saldo_Vawal=0;
        $saldo_Vakhir=$stockMasuk->sum('volume')-$stockKeluar->sum('volume');
        $saldo_Hawal=0;
        $saldo_Hakhir=$stockMasuk->sum('harga')-$stockKeluar->sum('harga');
        // dd($saldo_akhir);
        return view('stock.list-stock-masuk-keras',compact('stock'));
    }

    public function stock_opc(){
        $stock=StockLogOpc::get();
        // $stockMasuk=StockLogMasuk::where('ket','masuk')->get();
        // foreach($stockMasuk as $sM){}
        // $stockKeluar=StockLogMasuk::where('ket','keluar')->get();
        // foreach($stockKeluar as $sK){}

        // $saldo_Vawal=0;
        // $saldo_Vakhir=$stockMasuk->sum('volume')-$stockKeluar->sum('volume');
        // $saldo_Hawal=0;
        // $saldo_Hakhir=$stockMasuk->sum('harga')-$stockKeluar->sum('harga');
        // dd($saldo_akhir);
        return view('stock.list-stock-jadi-opc',compact('stock'));
    }
    public function stock_ppc(){
        $stock=StockLogPpc::get();
        // $stockMasuk=StockLogMasuk::where('ket','masuk')->get();
        // foreach($stockMasuk as $sM){}
        // $stockKeluar=StockLogMasuk::where('ket','keluar')->get();
        // foreach($stockKeluar as $sK){}

        // $saldo_Vawal=0;
        // $saldo_Vakhir=$stockMasuk->sum('volume')-$stockKeluar->sum('volume');
        // $saldo_Hawal=0;
        // $saldo_Hakhir=$stockMasuk->sum('harga')-$stockKeluar->sum('harga');
        // dd($saldo_akhir);
        return view('stock.list-stock-jadi-opc',compact('stock'));
    }
    public function stock_mk(){
        $stock=StockLogMk::get();
        // $stockMasuk=StockLogMasuk::where('ket','masuk')->get();
        // foreach($stockMasuk as $sM){}
        // $stockKeluar=StockLogMasuk::where('ket','keluar')->get();
        // foreach($stockKeluar as $sK){}

        // $saldo_Vawal=0;
        // $saldo_Vakhir=$stockMasuk->sum('volume')-$stockKeluar->sum('volume');
        // $saldo_Hawal=0;
        // $saldo_Hakhir=$stockMasuk->sum('harga')-$stockKeluar->sum('harga');
        // dd($saldo_akhir);
        return view('stock.list-stock-jadi-opc',compact('stock'));
    }
}