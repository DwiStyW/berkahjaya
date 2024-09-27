<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelian;
use App\Models\HasilProduk;
use App\Models\LogOpc;
use App\Models\Pembelian;
use App\Models\Produksi;
use App\Models\StockLogAmpulur;
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
        $produksi=Produksi::orderby('tanggal','desc')->get();
        $produksiGroup=DB::select("SELECT tanggal,supplier,kode_produksi,log_opc,harga_log,
                                sum(opc_m3) as sum_opc_m3,sum(opc_total) as sum_opc_total,sum(opcb_total) as sum_opcb_total,sum(ppc_total) as sum_ppc_total,sum(ampulur_total) as sum_ampulur_total,
                                count(*) as count from produksi
                                group by tanggal,supplier,kode_produksi,log_opc,harga_log order by tanggal desc");

        // dd($produksiGroup);
        return view('produksi.list-produksi',compact('produksi','produksiGroup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterproduk=HasilProduk::get();
        $datasupplier=LogOpc::where('ket','beli')
            ->where('status',null)
            ->orwhere('status','proses')
            ->get();
        foreach($datasupplier as $ds){
            $kode=$ds->kode;
            $detail=DetailPembelian::where('kode_pembelian',$kode)
            ->join('master_mentah','master_mentah.id','=','id_master_mentah')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','jenis_kayu')
            ->get();
            $arrvolS=[];
            $arrvolK=[];
            foreach ($detail as $dp) {
                if($dp->jenis_kayu=='sengon'){
                    array_push($arrvolS,$dp->vol);
                }
                if($dp->jenis_kayu=='keras'){
                    array_push($arrvolK,$dp->vol);
                }
            }
            $sum_Vsengon=array_sum($arrvolS);
            $sum_Vkeras=array_sum($arrvolK);
            if(($ds->stat_sengon!=null || $ds->stat_keras!=null) && $ds->status==null){
                $status='proses1';
            }else if(($ds->stat_sengon!=null || $ds->stat_keras!=null) && $ds->status=='proses'){
                $status='proses2';
            }else{
                $status=$ds->status;
            }
            $supplier[]=[
                'id'=>$ds->id,
                'kode'=>$ds->kode,
                'supplier'=>$ds->supplier,
                'uraian'=>$ds->uraian,
                'harga'=>$ds->harga,
                'sengon'=>$sum_Vsengon,
                'stat_sengon'=>$ds->stat_sengon,
                'harga_sengon'=>$ds->harga_sengon,
                'keras'=>$sum_Vkeras,
                'stat_keras'=>$ds->stat_keras,
                'harga_keras'=>$ds->harga_keras,
                'status'=>$status,
            ];
        };
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
                if($d_sup->sengon!=null && $d_sup->keras!=null){
                    if($request->checkSengon=="on" && $request->checkKeras=="on"){
                        $jenisKayu='campur';
                    }else if($request->checkSengon=="on" ){
                        $jenisKayu='sengon';
                    }else if($request->checkKeras=="on" ){
                        $jenisKayu='keras';
                    }else{
                        $jenisKayu='campur';
                    }
                }else{
                    if($request->checkSengon=="on" && $request->checkKeras=="on"){
                        $jenisKayu='campur';
                    }else if($request->checkSengon=="on" ){
                        $jenisKayu='sengon';
                    }else if($request->checkKeras=="on" ){
                        $jenisKayu='keras';
                    }
                }
            }
        }
        // dd();
        $sup_id=implode(",",$request_supplier);
        $sup_nama=implode(", ",$nama_sup);
        // salah
        $jumlah_log=$request->log_kubik;
        $harga_log=$request->log_harga;

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
        // dd($request);
        $data=[
            'kode_produksi'=>$kode,
            'tanggal'=>$newDate,
            'id_supplier'=>$sup_id,
            'supplier'=>$sup_nama,
            'log_opc'=>$jumlah_log,
            'jenis_kayu'=>$jenisKayu,
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

        // cek log
        // dd($request);


        // dd($dataLog);
        DB::beginTransaction();
        try{
            Temporary::create($data);
            $ceklog=LogOpc::whereIn('id',$request_supplier)->get();
            foreach ($ceklog as $cl){
                    if($request->checkSengon=="on" && $cl->stat_sengon!='L'){
                        $dataLog=[
                            'stat_sengon'=>'P',
                        ];
                        LogOpc::where('id',$cl->id)->update($dataLog);
                    } if($request->checkKeras=="on" && $cl->stat_keras!='L'){
                        // dd($cl->stat_keras);
                        $dataLog=[
                            'stat_keras'=>'P',
                        ];
                        LogOpc::where('id',$cl->id)->update($dataLog);
                    }

                    // selain sengon dan keras
                    if($cl->sengon==0 && $cl->keras==0){
                        $dataLog=[
                            'stat_sengon'=>'P',
                            'stat_keras'=>'P',
                        ];
                        LogOpc::where('id',$cl->id)->update($dataLog);
                    }
            }

            DB::commit();
            return redirect()->route('produksi.add');
        }catch(Exception $e){
            DB::rollBack();
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
        $arr_Vampulur=[];
        $arr_Hampulur=[];
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
                    'jenis_kayu'=>$opc->jenis_kayu,
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
            if($opc->id_produk=='5'){
                array_push($arr_Vampulur,$opc->pcs);
                array_push($arr_Hampulur,$opc->total);
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
        $sum_Vampulur=array_sum($arr_Vampulur);
        $sum_Hampulur=array_sum($arr_Hampulur);

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
        $dataStockAmpulur=[
            'kode'=>$opc->kode_produksi,
            'tanggal'=>$opc->tanggal,
            'supplier'=>$opc->supplier,
            'volume'=>$sum_Vampulur,
            'harga'=>$sum_Hampulur,
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
                // dd($caristockMasukKeras);
                if(count($caristockMasuk)!=0 && $clopc->stat_sengon=='P'){
                    foreach($caristockMasuk as $csMasuk){}
                    if($csMasuk->status==null){
                        $dataStockLogMasuk=[
                        'kode'=>$opc->kode_produksi,
                        'tanggal'=>$opc->tanggal,
                        'supplier'=>$clopc->supplier,
                        'volume'=>$csMasuk->volume,
                        'harga'=>$csMasuk->harga,
                        'ket'=>'keluar',
                    ];
                    StockLogMasuk::create($dataStockLogMasuk);
                    StockLogMasuk::where('kode',$clopc->kode)->update(['status'=>$opc->kode_produksi]);

                    LogOpc::where('id',$clopc->id)->update(['stat_sengon'=>'L']);

                    }
                }
                if(count($caristockMasukKeras)!=0 && $clopc->stat_keras=='P'){
                    foreach($caristockMasukKeras as $csMasukKeras){}
                    if($csMasukKeras->status==null){
                        $dataStockLogMasukKeras=[
                            'kode'=>$opc->kode_produksi,
                            'tanggal'=>$opc->tanggal,
                            'supplier'=>$clopc->supplier,
                            'volume'=>$csMasukKeras->volume,
                            'harga'=>$csMasukKeras->harga,
                            'ket'=>'keluar',
                        ];
                        StockLogMasukKeras::create($dataStockLogMasukKeras);
                        StockLogMasukKeras::where('kode',$clopc->kode)->update(['status'=>$opc->kode_produksi]);
                        // dd($clopc->id);
                        LogOpc::where('id',$clopc->id)->update(['stat_keras'=>'L']);
                    }
                }

                // selain sengon dan keras
                if(($clopc->sengon==0 && $clopc->stat_sengon=='P')&&($clopc->keras==0 && $clopc->stat_keras=='P') ){
                    LogOpc::where('id',$clopc->id)->update(['stat_sengon'=>'L','stat_keras'=>'L']);
                }

            }
            StockLogOpc::create($dataStockOpc);



            // END STOCK
            Produksi::insert($dataOPC);
            Temporary::whereIn('id',$idTempOpc)->update(['status'=>'move']);
            // ceklog
            $cekStatLog=LogOpc::whereIn('id',$idSup)->get();
            foreach($cekStatLog as $csl){
                if($csl->stat_sengon=='L' && $csl->stat_keras=='L'){
                    LogOpc::where('id',$csl->id)->update(['status'=>'L']);
                }else if(($csl->stat_sengon!='L' && $csl->sengon==0)&&$csl->stat_keras=='L'){
                    LogOpc::where('id',$csl->id)->update(['status'=>'L']);
                }else if(($csl->stat_keras!='L' && $csl->keras==0)&&$csl->stat_sengon=='L'){
                    LogOpc::where('id',$csl->id)->update(['status'=>'L']);
                }else if(($csl->stat_sengon!='L' && $csl->sengon!=0)&&$csl->stat_keras=='L'){
                    LogOpc::where('id',$csl->id)->update(['status'=>'proses']);
                }else if(($csl->stat_keras!='L' && $csl->keras!=0)&&$csl->stat_sengon=='L'){
                    LogOpc::where('id',$csl->id)->update(['status'=>'proses']);
                }else{
                    LogOpc::where('id',$csl->id)->update(['status'=>'proses']);
                }
            }


            // LogOpc::whereIn('id',$idSup)->update(['status'=>'L']);
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
                StockLogPpc::create($dataStockPpc);
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
                StockLogMk::create($dataStockMk);
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
                StockLogAmpulur::create($dataStockAmpulur);
            }


            DB::commit();
            return redirect("/produksi")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            DB::rollback();
            // dd($e);
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
        // iki edit submit
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
        // dd($produksi);
        foreach($produksi as $pro){}
        $data=[
            'kode_produksi'=>$kode,
            'tanggal'=>$pro->tanggal,
            'id_supplier'=>$pro->id_supplier,
            'supplier'=>$pro->supplier,
            'log_opc'=>$pro->log_opc,
            'harga_log'=>$pro->harga_log,
            'jenis_kayu'=>$pro->jenis_kayu,
            'id_produk'=>$request->produk,
            'pcs'=>$pcs,
            'ukuran'=>$ukuran,
            'size1'=>$request->ukuran1,
            'size2'=>$request->ukuran2,
            'size3'=>$request->ukuran3,
            'harga'=>$p->harga,
            'total'=>$total_harga,
        ];
        // dd($data);
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
                    'jenis_kayu'=>$opc->jenis_kayu,
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

        foreach($produksi as $p){
        }
        $id_supplier=$p->id_supplier;
        $idSup=explode(",",$id_supplier);
        $jenis_kayu=$p->jenis_kayu;
        // dump($idSup);
        // dd($kode);
        $data=[
            'stat_sengon'=>null,
            'stat_keras'=>null
        ];
        DB::beginTransaction();
        try{
            // cek kayu
            $kode_beli=[];
            $log=LogOpc::whereIn('id',$idSup)->get();
            foreach($log as $l){
                array_push($kode_beli,$l->kode);
                if($l->sengon==0 && $l->keras==0){
                    $idlog=$l->id;
                    LogOpc::where('id',$idlog)->update(['stat_sengon'=>null,'stat_keras'=>null,'status'=>null]);
                }
            }
            $cariSSk=StockLogMasuk::where('status',$kode)->get();
            $cariSKk=StockLogMasukKeras::where('status',$kode)->get();
// dump($cariSSk);
            foreach($cariSSk as $cssk){


                LogOpc::where('kode',$cssk->kode)->update(['stat_sengon'=>null]);
                $iniLognya=LogOpc::where('kode',$cssk->kode)->get();
                foreach($iniLognya as $lognya){}
                // dump(LogOpc::where('kode',$cssk->kode)->get());
                // dump($lognya->stat_sengon,$lognya->stat_keras);
                // if($cssk->stat_sengon==null && $cssk->stat_keras==null){
                if((($lognya->stat_sengon && $lognya->stat_keras)==null)){
                    LogOpc::where('kode',$cssk->kode)->update(['status'=>null]);
                    // dump(LogOpc::where('kode',$cssk->kode)->get());
                }
                if(($lognya->stat_sengon=='L' && $lognya->stat_keras=='P')){
                    LogOpc::where('kode',$cssk->kode)->update(['status'=>null]);
                    // dump(LogOpc::where('kode',$cssk->kode)->get());
                }
                if(($lognya->stat_sengon=='L' && $lognya->stat_keras==null)||($lognya->stat_sengon==null&&$lognya->stat_keras=='L')){
                    LogOpc::where('kode',$cssk->kode)->update(['status'=>'proses']);
                    // dump(LogOpc::where('kode',$cssk->kode)->get());
                }
                // dump(LogOpc::where('kode',$cssk->kode)->get());
                StockLogMasuk::where('kode',$cssk->kode)->update(['status'=>null]);
                StockLogMasuk::where('kode',$kode)->delete();
            }
            foreach($cariSKk as $cskk){
                //  dump(LogOpc::where('kode',$cssk->kode)->get());
                LogOpc::where('kode',$cskk->kode)->update(['stat_keras'=>null]);
                $iniLoglagi=LogOpc::where('kode',$cskk->kode)->get();
                foreach($iniLoglagi as $loglagi){}
                if($loglagi->stat_sengon==null && $loglagi->stat_keras==null){
                    LogOpc::where('kode',$cskk->kode)->update(['status'=>null]);

                }
                if(($loglagi->stat_sengon=='L'&&$loglagi->stat_keras=='P')){
                    LogOpc::where('kode',$cskk->kode)->update(['status'=>null]);
                }
                if(($loglagi->stat_sengon=='L'&&$loglagi->stat_keras==null)||($loglagi->stat_sengon==null&&$loglagi->stat_keras=='L')){
                    LogOpc::where('kode',$cskk->kode)->update(['status'=>'proses']);
                }
                // dump(LogOpc::where('kode',$cskk->kode)->get());
                StockLogMasukKeras::where('kode',$cskk->kode)->update(['status'=>null]);
                StockLogMasukKeras::where('kode',$kode)->delete();
            }

            // dd('mati');




            StockLogOpc::where('kode',$kode)->delete();
            StockLogPpc::where('kode',$kode)->delete();
            StockLogMk::where('kode',$kode)->delete();
            StockLogAmpulur::where('kode',$kode)->delete();




            Temporary::where('kode_produksi',$kode)->delete();
            Produksi::where('kode_produksi',$kode)->delete();

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

        $temp=Temporary::where('id',$de_id)->get();
        foreach($temp as $t){}
        $id_supplier=$t->id_supplier;
        $idSup=explode(",",$id_supplier);
        $jenis_kayu=$t->jenis_kayu;
        $kode=$t->kode_produksi;
        // $data=[
        //     'stat_sengon'=>null,
        //     'stat_keras'=>null
        // ];
        DB::beginTransaction();
        try{
            Temporary::where('id',$de_id)->delete();
            $tempNull=Temporary::where('kode_produksi',$kode)->get();
            // dd(count($tempNull),$jenis_kayu);
            if(count($tempNull)==0){
                if($jenis_kayu=='campur'){
                    LogOpc::whereIn('id',$idSup)->where('stat_sengon','P')->update(['stat_sengon'=>null]);
                    LogOpc::whereIn('id',$idSup)->where('stat_keras','P')->update(['stat_keras'=>null]);
                }if(($jenis_kayu=='sengon')){
                    LogOpc::whereIn('id',$idSup)->update(['stat_sengon'=>null]);
                }if(($jenis_kayu=='keras')){
                    LogOpc::whereIn('id',$idSup)->update(['stat_keras'=>null]);
                }
            }
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
        $temp=Temporary::where('kode_prodeuksi',$kode)->get();
        foreach($temp as $t){}
        $id_supplier=$t->id_supplier;
        $idSup=explode(",",$id_supplier);
        $jenis_kayu=$t->jenis_kayu;
        $data=[
            'stat_sengon'=>null,
            'stat_keras'=>null
        ];
        DB::beginTransaction();
        try{
            Temporary::where('id',$de_id)->delete();
            $tempNull=Temporary::where('kode_prodeuksi',$kode)->get();
            if(count($tempNull)==0){
                if($jenis_kayu=='campur'){
                    LogOpc::whereIn('id',$idSup)->update($data);
                }if(($jenis_kayu=='sengon')){
                    LogOpc::whereIn('id',$idSup)->update(['stat_sengon'=>null]);
                }if(($jenis_kayu=='keras')){
                    LogOpc::whereIn('id',$idSup)->update(['stat_keras'=>null]);
                }
            }
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