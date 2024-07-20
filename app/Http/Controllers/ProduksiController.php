<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelian;
use App\Models\HasilProduk;
use App\Models\LogOpc;
use App\Models\Pembelian;
use App\Models\Produksi;
use App\Models\StockLogMasuk;
use App\Models\StockLogMasukKeras;
use App\Models\StockLogMasukKeras260;
use App\Models\StockLogMasukSengon260;
use App\Models\StockLogMk;
use App\Models\StockLogOpc;
use App\Models\StockLogPpc;
use App\Models\Temporary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produksi=Produksi::get();
        return view('produksi.list-produksi',compact('produksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterproduk=HasilProduk::get();
        $supplier=LogOpc::where('ket','beli')
            ->where('status',null)
            ->get();
        // dd($supplier);
        $temporary=Temporary::join('hasil_produksi','hasil_produksi.id','=','id_produk')->where('status',null)->select('temporary.*','hasil_produksi','satuan')->get();
        // dd($temporary);
        return view('produksi.add-produksi',compact('supplier','masterproduk','temporary'));
    }

    // temporary menyimpan sementara data produksi
    public function temporary(Request $request){
        $produk_id=$request->produk;
        $produk=HasilProduk::where('id',$produk_id)->get();
        foreach ($produk as $p) {}
        if($p->satuan=='m'){
            $ukuran=$request->ukuran;
            if($p->hasil_produksi=='PPC'){
                $total_harga=122*122*80*$ukuran*$p->harga/100000000;
            }else if($p->hasil_produksi=='MK'){
                $total_harga=122*122*70*$ukuran*$p->harga/100000000;
            }

        }else if($p->satuan=='m3'){
            $hitumg_ukuran=$request->ukuran1*$request->ukuran2*$request->ukuran3*$request->pcs/100000000;
            $ukuran = number_format($hitumg_ukuran,4);
            $total_harga=$hitumg_ukuran*$p->harga;
        }else{
            $ukuran='';
            $total_harga=$request->pcs*1500;
        }
        $request_supplier=$request->supplier;
        // dd($request);
        $nama_sup=[];
        $jum_log=[];
        $harga_logopc=[];
        foreach($request_supplier as $sup){
            $data_sup=LogOpc::where('id',$sup)->get();
            foreach($data_sup as $d_sup){
                array_push($nama_sup,$d_sup->supplier);
                array_push($jum_log,$d_sup->uraian);
                array_push($harga_logopc,$d_sup->harga);
            }
        }
        $sup_id=implode(",",$request_supplier);
        $sup_nama=implode(", ",$nama_sup);
        $jumlah_log=array_sum($jum_log);
        $harga_log=array_sum($harga_logopc);

        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));
        // dd();
        if(isset($request->pcs)){
            $pcs=$request->pcs;
        }else{
            $pcs='';
        }

        $kode_bulan=date("My", strtotime($request_tanggal));
        // dump($kode_bulan);
        $carikode=Produksi::where('kode_produksi','like','%PRO'.$kode_bulan.'%')->orderby('id','desc')->limit(1)->get();
        // dump(count($carikode));
        if(count($carikode)==0){
            $kode='PRO'.$kode_bulan.'001';
        }else{
            foreach ($carikode as $ck){}
            $no_urut=sprintf("%03s",(int)substr($ck->kode_produksi,-3)+1);
            $kode='PRO'.$kode_bulan.$no_urut;
            // dump(substr($ck->kode_produksi,-3));
        }
        // dd($kode);
        $data=[
            'kode_produksi'=>$kode,
            'tanggal'=>$newDate,
            'id_supplier'=>$sup_id,
            'supplier'=>$sup_nama,
            'log_opc'=>$jumlah_log,
            'harga_log'=>$harga_log,
            'id_produk'=>$request->produk,
            'pcs'=>$pcs,
            'ukuran'=>$ukuran,
            'size1'=>$request->ukuran1,
            'size2'=>$request->ukuran2,
            'size3'=>$request->ukuran3,
            'harga'=>$p->harga,
            'total'=>$total_harga,
        ];

        try{
            Temporary::create($data);
            return redirect()->route('produksi.add');
        }catch(Exception $e){
            dd($e);
            return redirect()->route('produksi.add');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arr_Vopc=[];
        $arr_Hopc=[];
        $arr_Vppc=[];
        $arr_Hppc=[];
        $arr_Vmk=[];
        $arr_Hmk=[];
        $cariOPC=Temporary::where('status',null)
            ->get();
        // dd($cariOPC);
        $jumlah_opc=0;
        foreach($cariOPC as $c){
            if($c->id_produk=='1'){
                $jumlah_opc+=$c->ukuran;
            }
        }
        foreach($cariOPC as $opc){
            if($opc->id_produk=='1'){
                $idTempOpc[]=[
                    $opc->id,
                ];

                $persentase=$jumlah_opc/$opc->log_opc*100;
                $dataOPC[]=[
                    'kode_produksi'=>$opc->kode_produksi,
                    'tanggal'=>$opc->tanggal,
                    'id_supplier'=>$opc->id_supplier,
                    'supplier'=>$opc->supplier,
                    'persentase'=>$persentase,
                    'log_opc'=>$opc->log_opc,
                    'harga_log'=>$opc->harga_log,
                    'opc_pcs'=>$opc->pcs,
                    'opc_m3'=>$opc->ukuran,
                    'opc_harga'=>$opc->harga,
                    'opc_total'=>$opc->total,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s'),
                ];

                array_push($arr_Vopc,$opc->ukuran);
                array_push($arr_Hopc,$opc->total);
            }
            if($opc->id_produk=='3'){
                array_push($arr_Vppc,$opc->ukuran);
                array_push($arr_Hppc,$opc->total);
            }
            if($opc->id_produk=='4'){
                array_push($arr_Vmk,$opc->ukuran);
                array_push($arr_Hmk,$opc->total);
            }
        }
        $id_sup=$opc->id_supplier;
        $idSup=explode(",",$id_sup);

        // UNTUK STOCK MASUK BARANG JADI
        $sum_Vopc=array_sum($arr_Vopc);
        $sum_Hopc=array_sum($arr_Hopc);
        $sum_Vppc=(array_sum($arr_Vppc))*122*122*80/100000000;
        $sum_Hppc=array_sum($arr_Hppc);
        $sum_Vmk=array_sum($arr_Vmk)*122*122*70/100000000;
        $sum_Hmk=array_sum($arr_Hmk);

        $dataStockOpc=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vopc,
            'harga'=>$sum_Hopc,
            'ket'=>'masuk',
        ];

        $dataStockPpc=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vppc,
            'harga'=>$sum_Hppc,
            'ket'=>'masuk',
        ];

        $dataStockMk=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vmk,
            'harga'=>$sum_Hmk,
            'ket'=>'masuk',
        ];
        // END STOCK

        // dump($sum_Vopc,$sum_Hopc,$sum_Vppc,$sum_Hppc,$sum_Vmk,$sum_Hmk);
        // dd($dataStockOpc);
        DB::beginTransaction();
        try{
            // UNTUK STOCK KELUAR BARANG MENTAH
            $carilogopc=LogOpc::whereIn('id',$idSup)->get();
            foreach($carilogopc as $clopc){
                $caristockMasuk=StockLogMasuk::where('kode',$clopc->kode)->get();
                $caristockMasukKeras=StockLogMasukKeras::where('kode',$clopc->kode)->get();
                $caristockMasukSengon260=StockLogMasukSengon260::where('kode',$clopc->kode)->get();
                $caristockMasukKeras260=StockLogMasukKeras260::where('kode',$clopc->kode)->get();

                if(count($caristockMasuk)!=0){
                    foreach($caristockMasuk as $csMasuk){}
                    $dataStockLogMasuk=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasuk->volume,
                        'harga'=>$csMasuk->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasuk::create($dataStockLogMasuk);
                }
                if(count($caristockMasukKeras)!=0){
                    foreach($caristockMasukKeras as $csMasukKeras){}
                    $dataStockLogMasukKeras=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasukKeras->volume,
                        'harga'=>$csMasukKeras->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasukKeras::create($dataStockLogMasukKeras);
                }
                if(count($caristockMasukSengon260)!=0){
                    foreach($caristockMasukSengon260 as $csMasukSengon260){}
                    $dataStockLogMasukSengon260=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasukSengon260->volume,
                        'harga'=>$csMasukSengon260->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasukSengon260::create($dataStockLogMasukSengon260);
                }
                if(count($caristockMasukKeras260)!=0){
                    foreach($caristockMasukKeras260 as $csMasukKeras260){}
                    $dataStockLogMasukKeras260=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasukKeras260->volume,
                        'harga'=>$csMasukKeras260->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasukKeras260::create($dataStockLogMasukKeras260);
                }
            }
            StockLogOpc::create($dataStockOpc);
            StockLogPpc::create($dataStockPpc);
            StockLogMk::create($dataStockMk);

            // END STOCK
            Produksi::insert($dataOPC);
            Temporary::whereIn('id',$idTempOpc)->update(['status'=>'move']);
            LogOpc::whereIn('id',$idSup)->update(['status'=>'L']);
            // DB::commit();
            $dataProduksi=Produksi::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->orderby('id','DESC')
                ->get();
            foreach($dataProduksi as $dp){
            }
            // cari opcb
            $cariOPCB=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',2)
                ->where('status',null)
                ->get();
            if(count($cariOPCB)>0){
                foreach($cariOPCB as $opcB){}
                $dataOPCB=[
                    'opcb_pcs'=>$opcB->pcs,
                    'opcb_m3'=>$opcB->ukuran,
                    'opcb_harga'=>$opcB->harga,
                    'opcb_total'=>$opcB->total,
                ];
                Produksi::where('id',$dp->id)->update($dataOPCB);
                Temporary::where('id',$opcB->id)->update(['status'=>'move']);
            }
            // ppc
            $cariPPC=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',3)
                ->where('status',null)
                ->get();
            if(count($cariPPC)>0){
                foreach($cariPPC as $ppc){}
                $dataPPC=[
                    'ppc_m'=>$ppc->ukuran,
                    'ppc_harga'=>$ppc->harga,
                    'ppc_total'=>$ppc->total,
                ];
                Produksi::where('id',$dp->id)->update($dataPPC);
                Temporary::where('id',$ppc->id)->update(['status'=>'move']);
            }
            // cari mk
            $cariMK=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',4)
                ->where('status',null)
                ->get();
            if(count($cariMK)>0){
                foreach($cariMK as $mk){}
                $dataMK=[
                    'mk_m'=>$mk->ukuran,
                    'mk_harga'=>$mk->harga,
                    'mk_total'=>$mk->total,
                ];
                Produksi::where('id',$dp->id)->update($dataMK);
                Temporary::where('id',$mk->id)->update(['status'=>'move']);
            }
            // cari ompulur
            $cariAmpulur=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',5)
                ->where('status',null)
                ->get();

            if(count($cariAmpulur)>0){
                foreach($cariAmpulur as $amp){}
                $dataAMP=[
                    'ampulur_pcs'=>$amp->pcs,
                    'ampulur_total'=>$amp->total,
                ];
                Produksi::where('id',$dp->id)->update($dataAMP);
                Temporary::where('id',$amp->id)->update(['status'=>'move']);
            }


            DB::commit();
            return redirect("/produksi")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/produksi")->with('failed','Data gagal ditambahkan!');
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
    public function edit_perkode(string $id)
    {
        $kode=$id;
        $temporary=Temporary::where('kode_produksi',$kode)
            ->join('hasil_produksi','hasil_produksi.id','=','id_produk')
            ->select('temporary.*','hasil_produksi','satuan')
            ->get();
        return view('produksi.list-perkode',compact('temporary','kode'));
    }
    public function createPerkode($id)
    {
        $kode=$id;
        $masterproduk=HasilProduk::get();
        $produksi=Produksi::where('kode_produksi',$kode)->get();
        foreach($produksi as $p){}
        $id_supplier=$p->id_supplier;
        $idSup=explode(",",$id_supplier);
        $supplier=LogOpc::whereIn('id',$idSup)
            ->get();
        $tanggal=$p->tanggal;
        $log_opc=$p->log_opc;

        $temporary=Temporary::join('hasil_produksi','hasil_produksi.id','=','id_produk')->where('kode_produksi',$kode)->select('temporary.*','hasil_produksi','satuan')->get();
        // dd($supplier);
        return view('produksi.add-produksi-perkode',compact('supplier','masterproduk','temporary','tanggal','id_supplier','kode'));
    }

    public function temporary_perkode(Request $request,$kode){
        $produk_id=$request->produk;
        $produk=HasilProduk::where('id',$produk_id)->get();
        foreach ($produk as $p) {}
        if($p->satuan=='m'){
            $ukuran=$request->ukuran;
            if($p->hasil_produksi=='PPC'){
                $total_harga=122*122*80*$ukuran*$p->harga/100000000;
            }else if($p->hasil_produksi=='MK'){
                $total_harga=122*122*70*$ukuran*$p->harga/100000000;
            }

        }else if($p->satuan=='m3'){
            $hitumg_ukuran=$request->ukuran1*$request->ukuran2*$request->ukuran3*$request->pcs/100000000;
            $ukuran = number_format($hitumg_ukuran,4);
            $total_harga=$hitumg_ukuran*$p->harga;
        }else{
            $ukuran='';
            $total_harga=$request->pcs*1500;
        }

        if(isset($request->pcs)){
            $pcs=$request->pcs;
        }else{
            $pcs='';
        }
        $produksi=Produksi::where('kode_produksi',$kode)->get();
        foreach($produksi as $pro){}
        $data=[
            'kode_produksi'=>$kode,
            'tanggal'=>$pro->tanggal,
            'id_supplier'=>$pro->id_supplier,
            'supplier'=>$pro->supplier,
            'log_opc'=>$pro->log_opc,
            'harga_log'=>$pro->harga_log,
            'id_produk'=>$request->produk,
            'pcs'=>$pcs,
            'ukuran'=>$ukuran,
            'size1'=>$request->ukuran1,
            'size2'=>$request->ukuran2,
            'size3'=>$request->ukuran3,
            'harga'=>$p->harga,
            'total'=>$total_harga,
        ];

        DB::beginTransaction();
        try{
            Temporary::create($data);
            DB::commit();

            $masterproduk=HasilProduk::get();
            $produksi=Produksi::where('kode_produksi',$kode)->get();
            foreach($produksi as $p){}
            $id_supplier=$p->id_supplier;
            $idSup=explode(",",$id_supplier);
            $supplier=LogOpc::whereIn('id',$idSup)
                ->get();
            $tanggal=$p->tanggal;
            $log_opc=$p->log_opc;

            $temporary=Temporary::join('hasil_produksi','hasil_produksi.id','=','id_produk')->where('kode_produksi',$kode)->select('temporary.*','hasil_produksi','satuan')->get();
            // dd($supplier);
            return view('produksi.add-produksi-perkode',compact('supplier','masterproduk','temporary','tanggal','id_supplier','kode'));

        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return back()->with('failed','Data gagal ditambah!');
        }
    }


    public function store_perkode($kode)
    {
        $arr_Vopc=[];
        $arr_Hopc=[];
        $arr_Vppc=[];
        $arr_Hppc=[];
        $arr_Vmk=[];
        $arr_Hmk=[];
        DB::beginTransaction();
        Temporary::where('kode_produksi',$kode)->update(['status'=>null]);
        Produksi::where('kode_produksi',$kode)->delete();
        StockLogOpc::where('kode',$kode)->delete();
        StockLogPpc::where('kode',$kode)->delete();
        StockLogMk::where('kode',$kode)->delete();
        StockLogMasuk::where('kode',$kode)->delete();
        StockLogMasukKeras::where('kode',$kode)->delete();
        StockLogMasukKeras260::where('kode',$kode)->delete();
        StockLogMasukSengon260::where('kode',$kode)->delete();
        $cariOPC=Temporary::where('status',null)
            ->get();

        $jumlah_opc=0;
        foreach($cariOPC as $c){
            if($c->id_produk=='1'){
                $jumlah_opc+=$c->ukuran;
            }
        }
        foreach($cariOPC as $opc){
            if($opc->id_produk=='1'){
                $idTempOpc[]=[
                    $opc->id,
                ];

                $persentase=$jumlah_opc/$opc->log_opc*100;
                $dataOPC[]=[
                    'kode_produksi'=>$opc->kode_produksi,
                    'tanggal'=>$opc->tanggal,
                    'id_supplier'=>$opc->id_supplier,
                    'supplier'=>$opc->supplier,
                    'persentase'=>$persentase,
                    'log_opc'=>$opc->log_opc,
                    'harga_log'=>$opc->harga_log,
                    'opc_pcs'=>$opc->pcs,
                    'opc_m3'=>$opc->ukuran,
                    'opc_harga'=>$opc->harga,
                    'opc_total'=>$opc->total,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s'),
                ];

                array_push($arr_Vopc,$opc->ukuran);
                array_push($arr_Hopc,$opc->total);
            }
            if($opc->id_produk=='3'){
                array_push($arr_Vppc,$opc->ukuran);
                array_push($arr_Hppc,$opc->total);
            }
            if($opc->id_produk=='4'){
                array_push($arr_Vmk,$opc->ukuran);
                array_push($arr_Hmk,$opc->total);
            }
        }
        $id_sup=$opc->id_supplier;
        $idSup=explode(",",$id_sup);

        // UNTUK STOCK MASUK BARANG JADI
        $sum_Vopc=array_sum($arr_Vopc);
        $sum_Hopc=array_sum($arr_Hopc);
        $sum_Vppc=(array_sum($arr_Vppc))*122*122*80/100000000;
        $sum_Hppc=array_sum($arr_Hppc);
        $sum_Vmk=array_sum($arr_Vmk)*122*122*70/100000000;
        $sum_Hmk=array_sum($arr_Hmk);

        $dataStockOpc=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vopc,
            'harga'=>$sum_Hopc,
            'ket'=>'masuk',
        ];

        $dataStockPpc=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vppc,
            'harga'=>$sum_Hppc,
            'ket'=>'masuk',
        ];

        $dataStockMk=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vmk,
            'harga'=>$sum_Hmk,
            'ket'=>'masuk',
        ];

        $id_sup=$opc->id_supplier;
        $idSup=explode(",",$id_sup);

        try{
            // UNTUK STOCK KELUAR BARANG MENTAH
            $carilogopc=LogOpc::whereIn('id',$idSup)->get();
            foreach($carilogopc as $clopc){
                $caristockMasuk=StockLogMasuk::where('kode',$clopc->kode)->get();
                $caristockMasukKeras=StockLogMasukKeras::where('kode',$clopc->kode)->get();
                $caristockMasukSengon260=StockLogMasukSengon260::where('kode',$clopc->kode)->get();
                $caristockMasukKeras260=StockLogMasukKeras260::where('kode',$clopc->kode)->get();

                if(count($caristockMasuk)!=0){
                    foreach($caristockMasuk as $csMasuk){}
                    $dataStockLogMasuk=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasuk->volume,
                        'harga'=>$csMasuk->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasuk::create($dataStockLogMasuk);
                }
                if(count($caristockMasukKeras)!=0){
                    foreach($caristockMasukKeras as $csMasukKeras){}
                    $dataStockLogMasukKeras=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasukKeras->volume,
                        'harga'=>$csMasukKeras->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasukKeras::create($dataStockLogMasukKeras);
                }
                if(count($caristockMasukSengon260)!=0){
                    foreach($caristockMasukSengon260 as $csMasukSengon260){}
                    $dataStockLogMasukSengon260=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasukSengon260->volume,
                        'harga'=>$csMasukSengon260->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasukSengon260::create($dataStockLogMasukSengon260);
                }
                if(count($caristockMasukKeras260)!=0){
                    foreach($caristockMasukKeras260 as $csMasukKeras260){}
                    $dataStockLogMasukKeras260=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasukKeras260->volume,
                        'harga'=>$csMasukKeras260->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasukKeras260::create($dataStockLogMasukKeras260);
                }
            }
            if($sum_Vopc!=0){
                StockLogOpc::create($dataStockOpc);
            }
            if($sum_Vppc!=0){
                StockLogPpc::create($dataStockPpc);
            }
            if($sum_Vmk!=0){
                StockLogMk::create($dataStockMk);
            }

            Produksi::insert($dataOPC);
            Temporary::whereIn('id',$idTempOpc)->update(['status'=>'move']);
            LogOpc::whereIn('id',$idSup)->update(['status'=>'L']);
            // DB::commit();
            $dataProduksi=Produksi::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->orderby('id','DESC')
                ->get();
            foreach($dataProduksi as $dp){
            }
            // cari opcb
            $cariOPCB=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',2)
                ->where('status',null)
                ->get();
            if(count($cariOPCB)>0){
                foreach($cariOPCB as $opcB){}
                $dataOPCB=[
                    'opcb_pcs'=>$opcB->pcs,
                    'opcb_m3'=>$opcB->ukuran,
                    'opcb_harga'=>$opcB->harga,
                    'opcb_total'=>$opcB->total,
                ];
                Produksi::where('id',$dp->id)->update($dataOPCB);
                Temporary::where('id',$opcB->id)->update(['status'=>'move']);
            }
            // ppc
            $cariPPC=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',3)
                ->where('status',null)
                ->get();
                // dd();
            if(count($cariPPC)>0){
                foreach($cariPPC as $ppc){}
                $dataPPC=[
                    'ppc_m'=>$ppc->ukuran,
                    'ppc_harga'=>$ppc->harga,
                    'ppc_total'=>$ppc->total,
                ];
                Produksi::where('id',$dp->id)->update($dataPPC);
                Temporary::where('id',$ppc->id)->update(['status'=>'move']);
            }
            // cari mk
            $cariMK=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',4)
                ->where('status',null)
                ->get();
            if(count($cariMK)>0){
                foreach($cariMK as $mk){}
                $dataMK=[
                    'mk_m'=>$mk->ukuran,
                    'mk_harga'=>$mk->harga,
                    'mk_total'=>$mk->total,
                ];
                Produksi::where('id',$dp->id)->update($dataMK);
                Temporary::where('id',$mk->id)->update(['status'=>'move']);
            }
            // cari ompulur
            $cariAmpulur=Temporary::where('tanggal',$opc->tanggal)
                ->where('id_supplier',$opc->id_supplier)
                ->where('log_opc',$opc->log_opc)
                ->where('id_produk',5)
                ->where('status',null)
                ->get();

            if(count($cariAmpulur)>0){
                foreach($cariAmpulur as $amp){}
                $dataAMP=[
                    'ampulur_pcs'=>$amp->pcs,
                    'ampulur_total'=>$amp->total,
                ];
                Produksi::where('id',$dp->id)->update($dataAMP);
                Temporary::where('id',$amp->id)->update(['status'=>'move']);
            }


            DB::commit();
            return redirect("/produksi")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/produksi")->with('failed','Data gagal ditambahkan!');
        }
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kode=$id;
        $produksi=Produksi::where('kode_produksi',$kode)->get();
        foreach($produksi as $p){}
        $id_supplier=$p->id_supplier;
        $idSup=explode(",",$id_supplier);
        // dump($idSup);
        DB::beginTransaction();
        try{
            Temporary::where('kode_produksi',$kode)->delete();
            Produksi::where('kode_produksi',$kode)->delete();
            LogOpc::whereIn('id',$idSup)->update(['status'=>null]);
            DB::commit();
            return redirect("/produksi")->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/produksi")->with('failed','Data gagal dihapus!');
        }

    }

    public function destroy_temporary(string $id)
    {
        $de_id=Crypt::decrypt($id);//id temporary
        DB::beginTransaction();
        try{
            Temporary::where('id',$de_id)->delete();
            DB::commit();
            $masterproduk=HasilProduk::get();
            $supplier=LogOpc::where('ket','beli')
                ->where('status',null)
                ->get();
            $temporary=Temporary::join('hasil_produksi','hasil_produksi.id','=','id_produk')
                ->where('status',null)
                ->select('temporary.*','hasil_produksi','satuan')
                ->get();
            return redirect()->route('produksi.add',compact('supplier','masterproduk','temporary'));
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return back()->with('failed','Data gagal dihapus!');
        }
        // dump($de_id);
    }
    public function destroy_temporary_perkode($id,$kode)
    {
        $de_id=Crypt::decrypt($id);//id temporary
        DB::beginTransaction();
        try{
            Temporary::where('id',$de_id)->delete();
            DB::commit();

            return redirect('produksi-edit-perkode/'.$kode);
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return back()->with('failed','Data gagal dihapus!');
        }
        // dump($de_id);
    }

}