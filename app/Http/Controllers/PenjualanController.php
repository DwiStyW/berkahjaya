<?php

namespace App\Http\Controllers;

use App\Models\HasilProduk;
use App\Models\LogOpc;
use App\Models\Penjualan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualan=Penjualan::get();
        return view('penjualan.list-penjualan',compact('penjualan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mateng=HasilProduk::whereIn('id',[1,3,4])->get();
        return view('penjualan.add-penjualan',compact('mateng'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store(Request $request)
    {
        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));

        $kode_bulan=date("My", strtotime($request_tanggal));
        // dump($kode_bulan);
        $carikode=Penjualan::where('kode_penjualan','like','%J'.$kode_bulan.'%')->orderby('id','desc')->limit(1)->get();
        // dump(count($carikode));
        if(count($carikode)==0){
            $kode='J'.$kode_bulan.'001';
        }else{
            foreach ($carikode as $ck){}
            $no_urut=sprintf("%03s",(int)substr($ck->kode_penjualan,-3)+1);
            $kode='J'.$kode_bulan.$no_urut;
        }
        $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
        $total_harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->total_harga);

        $dataPenjualan=[
            'kode_penjualan'=>$kode,
            'tanggal'=>$newDate,
            'supplier'=>$request->supplier,
            'alamat'=>$request->alamat,
            'grade'=>$request->grade,
            'jenis_kayu'=>$request->jenis_kayu,
            'ukuran1'=>$request->ukuran1,
            'ukuran2'=>$request->ukuran2,
            'ukuran3'=>$request->ukuran3,
            'pcs'=>$request->pcs,
            'crate'=>$request->crate,
            'vol_m3'=>$request->vol,
            'harga_vol_m3'=>$harga,
            'total_harga'=>$total_harga,
        ];
        $dataLog=[
            'kode'=>$kode,
            'tanggal'=>$newDate,
            'supplier'=>$request->supplier,
            'uraian'=>$request->vol,
            'harga'=>$total_harga,
            'ket'=>'jual',
        ];
        // dd($dataPenjualan);
        DB::beginTransaction();
        try{
            Penjualan::create($dataPenjualan);
            LogOpc::create($dataLog);
            DB::commit();
            return redirect("/penjualan")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/penjualan")->with('failed','Data gagal ditambahkan!');
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
    public function edit(string $id)
    {
        $de_id=Crypt::decrypt($id);
        $penjualan=Penjualan::where('id',$de_id)->get();

        return view('penjualan.edit-penjualan',compact('penjualan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $de_id=Crypt::decrypt($id);
        $Penjualan=Penjualan::where('id',$de_id)->get();
        foreach($Penjualan as $p){}
        $kodePenjualan=$p->kode_penjualan;

        $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
        $total_harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->total_harga);

        $dataPenjualan=[
            'supplier'=>$request->supplier,
            'alamat'=>$request->alamat,
            'grade'=>$request->grade,
            'jenis_kayu'=>$request->jenis_kayu,
            'ukuran1'=>$request->ukuran1,
            'ukuran2'=>$request->ukuran2,
            'ukuran3'=>$request->ukuran3,
            'pcs'=>$request->pcs,
            'crate'=>$request->crate,
            'vol_m3'=>$request->vol,
            'harga_vol_m3'=>$harga,
            'total_harga'=>$total_harga,
        ];
        $dataLog=[
            'supplier'=>$request->supplier,
            'uraian'=>$request->vol,
            'harga'=>$total_harga,
            'ket'=>'jual',
        ];

        DB::beginTransaction();
        try{
            Penjualan::where('id',$de_id)->update($dataPenjualan);
            LogOpc::where('kode',$kodePenjualan)->update($dataLog);
            DB::commit();
            return redirect("/penjualan")->with('success','Data berhasil diubah!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/penjualan")->with('failed','Data gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $de_id=Crypt::decrypt($id);//id penjualan
    $penjualan=Penjualan::where('id',$de_id)->get();
    foreach($penjualan as $p){}
    $kodePenjualan=$p->kode_penjualan;

        DB::beginTransaction();
        try{
            Penjualan::where('id',$de_id)->delete();
            LogOpc::where('kode',$kodePenjualan)->delete();
            DB::commit();
            return redirect("/penjualan")->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/penjualan")->with('failed','Data gagal dihapus!');
        }
    }

    public function detail_penjualan($id){
        $de_id=Crypt::decrypt($id);//id penjualan
        $penjualan=Penjualan::where('id',$de_id)->get();

        return view('penjualan.detail_penjualan',compact('penjualan','id'));
    }

    public function printPenjualan($id){
        $de_id=Crypt::decrypt($id);//id penjualan
        $penjualan=Penjualan::where('id',$de_id)->get();

        return view('penjualan.print_penjualan',compact('penjualan','id'));
    }
}