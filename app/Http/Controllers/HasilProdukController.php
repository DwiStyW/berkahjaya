<?php

namespace App\Http\Controllers;

use App\Models\HasilProduk;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class HasilProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hasilproduk = HasilProduk::get();
        return view('hasil-produk.list-hasil-produk',compact('hasilproduk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hasil-produk.add-hasil-produk');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $data=[
                'hasil_produksi'=>$request->hasil_produksi,
                'satuan'=>$request->satuan,
                'harga'=>$request->harga,
            ];
        // dd($id);
        try{
            HasilProduk::create($data);
            return redirect("/hasilproduk")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/hasilproduk")->with('failed','Data gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $de_id=Crypt::decrypt($id);
        $produk=HasilProduk::where('id',$de_id)->get();

        return view('hasil-produk.edit-hasil-produk',compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $de_id=Crypt::decrypt($id);
        $data=[
                'hasil_produksi'=>$request->hasil_produksi,
                'satuan'=>$request->satuan,
                'harga'=>$request->harga
            ];
        // dd($id);
        try{
            HasilProduk::where('id',$de_id)->update($data);
            return redirect("/hasilproduk")->with('success','Data berhasil diedit!');
        }catch(Exception $e){
            dd($e);
            return redirect("/hasilproduk")->with('failed','Data gagal diedit!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        // dd($id);
        $de_id=Crypt::decrypt($id);
        try{
            HasilProduk::where('id',$de_id)->delete();
            return redirect("/hasilproduk")->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            return redirect("/hasilproduk")->with('failed','Data gagal dihapus!');
        }
    }

}