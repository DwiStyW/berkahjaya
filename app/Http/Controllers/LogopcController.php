<?php

namespace App\Http\Controllers;

use App\Models\LogOpc;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LogopcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datalog=LogOpc::orderby('tanggal','asc')->get();
        return view('log-opc.list-logopc',compact('datalog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('log-opc.add-logopc');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dump($request->kategori);
        if($request->kategori=='pembelian'){

            DB::beginTransaction();
            try{
                PembelianController::store($request);
                DB::commit();
                return redirect("/logopc")->with('success','Data berhasil ditambahkan!');
            }catch(Exception $e){
                DB::rollback();
                dd($e);
                return redirect("/logopc")->with('failed','Data gagal ditambahkan!');
            }
        }else if($request->kategori=='penjualan'){
            DB::beginTransaction();
            try{
                PenjualanController::store($request);
                DB::commit();
                return redirect("/logopc")->with('success','Data berhasil ditambahkan!');
            }catch(Exception $e){
                DB::rollback();
                dd($e);
                return redirect("/logopc")->with('failed','Data gagal ditambahkan!');
            }
        }else{
            $request_tanggal=$request->tanggal;
            $newDate = date("Y-m-d", strtotime($request_tanggal));

            $kode_bulan=date("My", strtotime($request_tanggal));
            // dump($kode_bulan);
            $carikode=LogOpc::where('kode','like','%S'.$kode_bulan.'%')->orderby('id','desc')->limit(1)->get();
            // dump(count($carikode));
            if(count($carikode)==0){
                $kode='S'.$kode_bulan.'001';
            }else{
                foreach ($carikode as $ck){}
                $no_urut=sprintf("%03s",(int)substr($ck->kode_pembelian,-3)+1);
                $kode='S'.$kode_bulan.$no_urut;
            }
            $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
            $data=[
            'kode'=>$kode,
            'tanggal'=>$newDate,
            'supplier'=>$request->supplier,
            'uraian'=>$request->vol,
            'harga'=>$harga,
            'ket'=>'stock',
            ];

            // dump($data);
            DB::beginTransaction();
            try{
                LogOpc::create($data);
                DB::commit();
                return redirect("/logopc")->with('success','Data berhasil ditambahkan!');
            }catch(Exception $e){
                DB::rollback();
                dd($e);
                return redirect("/logopc")->with('failed','Data gagal ditambahkan!');
            }
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
        $logopc=LogOpc::where('id',$de_id)->get();
        foreach($logopc as $lo);
        if($lo->ket=='beli'){
            $pembelian=Pembelian::where('kode_pembelian',$lo->kode)->get();
            // dd($pembelian);
            return view('log-opc.edit-pembelian-logopc',compact('pembelian'));
        }else if($lo->ket=='jual'){
            $penjualan=Penjualan::where('kode_penjualan',$lo->kode)->get();
            // dd($pembelian);
            return view('log-opc.edit-penjualan-logopc',compact('penjualan'));
        }else{
            return view('log-opc.edit-stock-logopc',compact('logopc'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePembelian(Request $request, $id)
    {
        $de_id=Crypt::decrypt($id);
        $pembelian=Pembelian::where('id',$de_id)->get();
        foreach($pembelian as $p){}
        $kodePembelian=$p->kode_pembelian;

        $cekLog=LogOpc::where('kode',$kodePembelian)->where('status','L')->get();
        if(count($cekLog)==0){
            $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
            $dataPembelian=[
                'supplier'=>$request->supplier,
                'vol'=>$request->vol,
                'total_harga'=>$harga,
            ];
            $dataLog=[
                'supplier'=>$request->supplier,
                'uraian'=>$request->vol,
                'harga'=>$harga,
            ];

            DB::beginTransaction();
            try{
                Pembelian::where('id',$de_id)->update($dataPembelian);
                LogOpc::where('kode',$kodePembelian)->update($dataLog);
                DB::commit();
                return redirect("/logopc")->with('success','Data berhasil diubah!');
            }catch(Exception $e){
                DB::rollback();
                dd($e);
                return redirect("/logopc")->with('failed','Data gagal diubah!');
            }
        }else{
            return redirect("/logopc")->with('failed','Data gagal diubah!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
        }
    }
    public function updatePenjualan(Request $request, $id)
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
        ];

        DB::beginTransaction();
        try{
            Penjualan::where('id',$de_id)->update($dataPenjualan);
            LogOpc::where('kode',$kodePenjualan)->update($dataLog);
            DB::commit();
            return redirect("/logopc")->with('success','Data berhasil diubah!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/logopc")->with('failed','Data gagal diubah!');
        }
    }
    public function updateStock(Request $request, $id)
    {
        $de_id=Crypt::decrypt($id);
        $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
        $dataLog=[
            'supplier'=>$request->supplier,
            'uraian'=>$request->uraian,
            'harga'=>$harga,
        ];
        DB::beginTransaction();
        try{
            LogOpc::where('id',$de_id)->update($dataLog);
            DB::commit();
            return redirect("/logopc")->with('success','Data berhasil diubah!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/logopc")->with('failed','Data gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $de_id=Crypt::decrypt($id);//id logopc
        $logopc=LogOpc::where('id',$de_id)->get();
        foreach ($logopc as $lo){}
        if($lo->ket=='beli'){
            $kodePembelian=$lo->kode;
            $cekLog=LogOpc::where('kode',$kodePembelian)->where('status','L')->get();
            if(count($cekLog)==0){
                DB::beginTransaction();
                try{
                    Pembelian::where('kode_pembelian',$kodePembelian)->delete();
                    LogOpc::where('id',$de_id)->delete();
                    DB::commit();
                    return redirect("/logopc")->with('success','Data berhasil dihapus!');
                }catch(Exception $e){
                    DB::rollback();
                    dd($e);
                    return redirect("/logopc")->with('failed','Data gagal dihapus!');
                }
            }else{
                return redirect("/logopc")->with('failed','Data gagal dihapus!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
            }
        }else if($lo->ket=='jual'){
            $kodePenjualan=$lo->kode;
             DB::beginTransaction();
                try{
                    Penjualan::where('kode_penjualan',$kodePenjualan)->delete();
                    LogOpc::where('id',$de_id)->delete();
                    DB::commit();
                    return redirect("/logopc")->with('success','Data berhasil dihapus!');
                }catch(Exception $e){
                    DB::rollback();
                    dd($e);
                    return redirect("/logopc")->with('failed','Data gagal dihapus!');
                }
        }else{
            DB::beginTransaction();
                try{
                    LogOpc::where('id',$de_id)->delete();
                    DB::commit();
                    return redirect("/logopc")->with('success','Data berhasil dihapus!');
                }catch(Exception $e){
                    DB::rollback();
                    dd($e);
                    return redirect("/logopc")->with('failed','Data gagal dihapus!');
                }
        }
    }
}