<?php

namespace App\Http\Controllers;

use App\Models\StockLogMasuk;
use App\Models\StockLogMasukKeras;
use Illuminate\Http\Request;

class StockController extends Controller
{
    //
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
}