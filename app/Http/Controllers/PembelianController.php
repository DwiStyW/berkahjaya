<?php

namespace App\Http\Controllers;

use App\Models\DetailMasterMentah;
use App\Models\DetailPembelian;
use App\Models\LogOpc;
use App\Models\MasterMentah;
use App\Models\Pembelian;
use App\Models\Produksi;
use App\Models\StockLogMasuk;
use App\Models\StockLogMasukKeras;
use App\Models\StockLogMasukKeras260;
use App\Models\StockLogMasukSengon260;
use App\Models\StockLogMk;
use App\Models\StockLogOpc;
use App\Models\StockLogPpc;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembelian=Pembelian::orderby('id','desc')->get();
        return view('pembelian.list-pembelian',compact('pembelian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mastermentah=MasterMentah::get();
        $detail_pembelian=DetailPembelian::where('kode_pembelian',null)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
            ->orderby('model','asc')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
            'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan')
            ->get();
        // $detail_pembelian_g=DB::select('SELECT count(*),kode_pembelian,tanggal,supplier,sum(jumlah) as jumlah,sum(vol) as vol,sum(total_harga) as total_harga,detail_pembelian.id_master_mentah,id_model,detail_pembelian.harga_model,detail_pembelian.status,
        //     master_mentah.jenis_muatan,detail_master_mentah.kelas_model,detail_master_mentah.model,detail_master_mentah.pakem,detail_master_mentah.pakem_pembulatan
        //     From detail_pembelian
        //     join master_mentah on id_master_mentah=master_mentah.id
        //     join detail_master_mentah on id_model=detail_master_mentah.id
        //     where kode_pembelian is null
        //     group by kode_pembelian,tanggal,supplier,detail_pembelian.id_master_mentah,id_model,detail_pembelian.status,detail_pembelian.harga_model,
        //     master_mentah.jenis_muatan,detail_master_mentah.kelas_model,detail_master_mentah.model,detail_master_mentah.pakem,detail_master_mentah.pakem_pembulatan
        //     ');
        // dd($detail_pembelian_g);
        $detail_pembelian_group=DetailPembelian::where('kode_pembelian',null)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('id_master_mentah','jenis_muatan')
            ->select('id_master_mentah','jenis_muatan')
            ->get();

        $detail_pembelian_group_truk=DetailPembelian::where('kode_pembelian',null)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('no_truk')
            ->select('no_truk')
            ->get();
            // dd($detail_pembelian_group);
        $supplier=Supplier::get();
        return view('pembelian.add-pembelian',compact('mastermentah','detail_pembelian','detail_pembelian_group','detail_pembelian_group_truk','supplier'));
    }

    public function getModel(Request $request){
        $id=$request->id_master;
        $model=DetailMasterMentah::where('id_master_mentah',$id)->orderby('model','asc')->get();
        return $model;
    }

    public function getDetailmodel(Request $request){
        $id=$request->id_model;
        $model=DetailMasterMentah::where('id',$id)->get();
        // dd($model);
        return $model;
    }
    /**
     * Store a newly created resource in storage.
     */

    public function detail_store(Request $request){
        // dd($request);

        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));
        $length=$request->indexLoop;
        for($i=0;$i<$length;$i++){
            $rq_model='id_model'.$i;
            $rq_afkir='afkir'.$i;
            $rq_jumlah='jumlah'.$i;
            $rq_volume='volume'.$i;
            $rq_harga='harga'.$i;
            $rq_rupiah='rupiah'.$i;
            if($request->$rq_jumlah!=null){
                $total_harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->$rq_rupiah);

                if($request->$rq_afkir=='on'){
                    $status='afkir';
                }else{
                    $status=null;
                }
                $data[]=[
                    'tanggal'=>$newDate,
                    'supplier'=>$request->supplier,
                    'no_truk'=>$request->no_truk,
                    'id_master_mentah'=>$request->id_master_mentah,
                    'id_model'=>$request->$rq_model,
                    'jumlah'=>$request->$rq_jumlah,
                    'vol'=>$request->$rq_volume,
                    'harga_model'=>$request->$rq_harga,
                    'total_harga'=>$total_harga,
                    'status'=>$status,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s"),
                ];
            }
        }
// dd($data);
        try{
            DetailPembelian::insert($data);

            return redirect()->route('pembelian.add');
        }catch(Exception $e){
            dd($e);
            return redirect()->route('pembelian.add');
        }

    }

    public static function store()
    {
        $data_detail_pembelian=DetailPembelian::where('kode_pembelian',null)->get();
        $data_truk=DetailPembelian::where('kode_pembelian',null)->groupby('no_truk')->select('no_truk')->get();
        // dd($data_truk);
        $arr_vol=[];
        $arr_total=[];

        $arr_Vsuper=[];
        $arr_Vsengon260=[];
        $arr_Vkeras260=[];
        $arr_Vkeras=[];
        $arr_Vlog100=[];
        $arr_Vlog130=[];
        $arr_Vkalimantan=[];

        $arr_Hsuper=[];
        $arr_Hsengon260=[];
        $arr_Hkeras260=[];
        $arr_Hkeras=[];
        $arr_Hlog100=[];
        $arr_Hlog130=[];
        $arr_Hkalimantan=[];

        $arr_notruk=[];
        foreach($data_detail_pembelian as $ddp){
            array_push($arr_vol,$ddp->vol);
            //
            array_push($arr_total,$ddp->total_harga);
            if($ddp->id_master_mentah=='1'){
                array_push($arr_Vsuper,$ddp->vol);
                array_push($arr_Hsuper,$ddp->total_harga);
            }else if($ddp->id_master_mentah=='2'){
                array_push($arr_Vsengon260,$ddp->vol);
                array_push($arr_Hsengon260,$ddp->total_harga);
            }else if($ddp->id_master_mentah=='3'){
                array_push($arr_Vkeras260,$ddp->vol);
                array_push($arr_Hkeras260,$ddp->total_harga);
            }else if($ddp->id_master_mentah=='4'){
                array_push($arr_Vkeras,$ddp->vol);
                array_push($arr_Hkeras,$ddp->total_harga);
            }else if($ddp->id_master_mentah=='5'){
                array_push($arr_Vlog100,$ddp->vol);
                array_push($arr_Hlog100,$ddp->total_harga);
            }else if($ddp->id_master_mentah=='6'){
                array_push($arr_Vlog130,$ddp->vol);
                array_push($arr_Hlog130,$ddp->total_harga);
            }else if($ddp->id_master_mentah=='7'){
                array_push($arr_Vkalimantan,$ddp->vol);
                array_push($arr_Hkalimantan,$ddp->total_harga);
            }
        }

        foreach($data_truk as $dt){
            array_push($arr_notruk,$dt->no_truk);
        }
        $sum_vol=array_sum($arr_vol);
        $sum_total=array_sum($arr_total);

        $sum_Vsuper=array_sum($arr_Vsuper);
        $sum_Vsengon260=array_sum($arr_Vsengon260);
        $sum_Vkeras260=array_sum($arr_Vkeras260);
        $sum_Vkeras=array_sum($arr_Vkeras);
        $sum_Vlog100=array_sum($arr_Vlog100);
        $sum_Vlog130=array_sum($arr_Vlog130);
        $sum_Vkalimantan=array_sum($arr_Vkalimantan);

        $sum_Hsuper=array_sum($arr_Hsuper);
        $sum_Hsengon260=array_sum($arr_Hsengon260);
        $sum_Hkeras260=array_sum($arr_Hkeras260);
        $sum_Hkeras=array_sum($arr_Hkeras);
        $sum_Hlog100=array_sum($arr_Hlog100);
        $sum_Hlog130=array_sum($arr_Hlog130);
        $sum_Hkalimantan=array_sum($arr_Hkalimantan);

        $no_truk=implode(",",$arr_notruk);
        $total_truk=count($arr_notruk);
        // dd($sum_Vsuper,$sum_Vsengon260,$sum_Vkeras260,$sum_Vkeras);
        $request_tanggal=$ddp->tanggal;
        // $newDate = date("Y-m-d", strtotime($request_tanggal));

        $kode_bulan=date("My", strtotime($request_tanggal));
        // // dump($kode_bulan);
        $carikode=Pembelian::where('kode_pembelian','like','%B'.$kode_bulan.'%')->orderby('id','desc')->limit(1)->get();
        // // dump(count($carikode));
        if(count($carikode)==0){
            $kode='B'.$kode_bulan.'001';
        }else{
            foreach ($carikode as $ck){}
            $no_urut=sprintf("%03s",(int)substr($ck->kode_pembelian,-3)+1);
            $kode='B'.$kode_bulan.$no_urut;
        }

        // dd($kode);
        // $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
        $dataPembelian=[
            'kode_pembelian'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'jumlah_truk'=>$total_truk,
            'no_truk'=>$no_truk,
            'vol'=>$sum_vol,
            'total_harga'=>$sum_total,
        ];
        // dd($dataPembelian);
        $dataLog=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'uraian'=>$sum_vol,
                'sengon'=>$sum_Vsuper+$sum_Vsengon260+$sum_Vlog100+$sum_Vlog130+$sum_Vkalimantan,
                'harga_sengon'=>$sum_Hsuper+$sum_Hsengon260+$sum_Hlog100+$sum_Hlog130+$sum_Hkalimantan,
                'keras'=>$sum_Vkeras260+$sum_Vkeras,
                'harga_keras'=>$sum_Hkeras260+$sum_Hkeras,
                'harga'=>$sum_total,
                'ket'=>'beli',
            ];
            // dd($dataLog);
            $dataStockLogMasuk=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vsuper+$sum_Vsengon260+$sum_Vlog100+$sum_Vlog130+$sum_Vkalimantan,
                'harga'=>$sum_Hsuper+$sum_Hsengon260+$sum_Hlog100+$sum_Hlog130+$sum_Hkalimantan,
                'ket'=>'masuk',
            ];

            $dataStockLogMasukKeras=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vkeras260+$sum_Vkeras,
                'harga'=>$sum_Hkeras260+$sum_Hkeras,
                'ket'=>'masuk',
            ];

        // // dd($dataPembelian);
        DB::beginTransaction();
        try{
            Pembelian::create($dataPembelian);
            LogOpc::create($dataLog);
            if($sum_Vsuper+$sum_Vsengon260+$sum_Vlog100+$sum_Vlog130+$sum_Vkalimantan!=0){
                    StockLogMasuk::create($dataStockLogMasuk);
                }
                if($sum_Vkeras260+$sum_Vkeras!=0){
                    StockLogMasukKeras::create($dataStockLogMasukKeras);
                }
            DetailPembelian::where('kode_pembelian',null)->update(['kode_pembelian'=>$kode]);
            DB::commit();
            return redirect("/pembelian")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/pembelian")->with('failed','Data gagal ditambahkan!');
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
        // cek
        $kode=Crypt::decrypt($id);
        $cekLog=LogOpc::where('kode',$kode)->where('status','L')->get();

        if(count($cekLog)==0){

            $pembelian=Pembelian::where('kode_pembelian',$kode)->get();
            // dd($id);
            foreach($pembelian as $p){}
            $tanggal=$p->tanggal;
            $supplier=$p->supplier;
            // dd($detail_pembelian);
            $mastermentah=MasterMentah::where('status','aktif')->get();
            $detail_pembelian=DetailPembelian::where('kode_pembelian',$kode)
                ->join('master_mentah','id_master_mentah','=','master_mentah.id')
                ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
                ->orderby('model','asc')
                ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
                'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan')
                ->get();
            // dd($detail_pembelian);
            $detail_pembelian_group=DetailPembelian::where('kode_pembelian',$kode)
                ->join('master_mentah','id_master_mentah','=','master_mentah.id')
                ->groupby('id_master_mentah','jenis_muatan')
                ->select('id_master_mentah','jenis_muatan')
                ->get();
                // dd($tanggal);
            return view('pembelian.edit-pembelian',compact('pembelian','tanggal','supplier','mastermentah','detail_pembelian','detail_pembelian_group'));
        }else{
            return redirect("/pembelian")->with('failed','Data tidak bisa diedit!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( $id,$tanggal,$supplier)
    {
        $kode=Crypt::decrypt($id);
        $cekLog=LogOpc::where('kode',$kode)->where('status','L')->where('status','proses')->get();
        // dd($kode,$tanggal,$supplier);
        if(count($cekLog)==0){
            DB::beginTransaction();

            DetailPembelian::where('kode_pembelian',$kode)->update(['kode_pembelian'=>null]);
            Pembelian::where('kode_pembelian',$kode)->delete();
            LogOpc::where('kode',$kode)->delete();
            StockLogMasuk::where('kode',$kode)->delete();
            StockLogMasukKeras::where('kode',$kode)->delete();
            StockLogMasukKeras260::where('kode',$kode)->delete();
            StockLogMasukSengon260::where('kode',$kode)->delete();

            $data_detail_pembelian=DetailPembelian::where('kode_pembelian',null)->get();
            // dd($data_detail_pembelian);
            $arr_vol=[];
            $arr_total=[];

            $arr_Vsuper=[];
            $arr_Vsengon260=[];
            $arr_Vkeras260=[];
            $arr_Vkeras=[];
            $arr_Vlog100=[];
            $arr_Vlog130=[];
            $arr_Vkalimantan=[];

            $arr_Hsuper=[];
            $arr_Hsengon260=[];
            $arr_Hkeras260=[];
            $arr_Hkeras=[];
            $arr_Hlog100=[];
            $arr_Hlog130=[];
            $arr_Hkalimantan=[];

            $arr_notruk=[];
            foreach($data_detail_pembelian as $ddp){
                array_push($arr_vol,$ddp->vol);
                //
                array_push($arr_total,$ddp->total_harga);
                if($ddp->id_master_mentah=='1'){
                    array_push($arr_Vsuper,$ddp->vol);
                    array_push($arr_Hsuper,$ddp->total_harga);
                }else if($ddp->id_master_mentah=='2'){
                    array_push($arr_Vsengon260,$ddp->vol);
                    array_push($arr_Hsengon260,$ddp->total_harga);
                }else if($ddp->id_master_mentah=='3'){
                    array_push($arr_Vkeras260,$ddp->vol);
                    array_push($arr_Hkeras260,$ddp->total_harga);
                }else if($ddp->id_master_mentah=='4'){
                    array_push($arr_Vkeras,$ddp->vol);
                    array_push($arr_Hkeras,$ddp->total_harga);
                }else if($ddp->id_master_mentah=='5'){
                    array_push($arr_Vlog100,$ddp->vol);
                    array_push($arr_Hlog100,$ddp->total_harga);
                }else if($ddp->id_master_mentah=='6'){
                    array_push($arr_Vlog130,$ddp->vol);
                    array_push($arr_Hlog130,$ddp->total_harga);
                }else if($ddp->id_master_mentah=='7'){
                    array_push($arr_Vkalimantan,$ddp->vol);
                    array_push($arr_Hkalimantan,$ddp->total_harga);
                }
            }
            $sum_vol=array_sum($arr_vol);
            $sum_total=array_sum($arr_total);

            $sum_Vsuper=array_sum($arr_Vsuper);
            $sum_Vsengon260=array_sum($arr_Vsengon260);
            $sum_Vkeras260=array_sum($arr_Vkeras260);
            $sum_Vkeras=array_sum($arr_Vkeras);
            $sum_Vlog100=array_sum($arr_Vlog100);
            $sum_Vlog130=array_sum($arr_Vlog130);
            $sum_Vkalimantan=array_sum($arr_Vkalimantan);

            $sum_Hsuper=array_sum($arr_Hsuper);
            $sum_Hsengon260=array_sum($arr_Hsengon260);
            $sum_Hkeras260=array_sum($arr_Hkeras260);
            $sum_Hkeras=array_sum($arr_Hkeras);
            $sum_Hlog100=array_sum($arr_Hlog100);
            $sum_Hlog130=array_sum($arr_Hlog130);
            $sum_Hkalimantan=array_sum($arr_Hkalimantan);

            $data_truk=DetailPembelian::where('kode_pembelian',null)->whereNotNull('no_truk')->groupby('no_truk')->select('no_truk')->get();

            foreach($data_truk as $dt){
            array_push($arr_notruk,$dt->no_truk);
        }
                    $no_truk=implode(",",$arr_notruk);
        $total_truk=count($arr_notruk);
            // dd($sum_Vsuper,$sum_Vsengon260,$sum_Vkeras260,$sum_Vkeras);
            // $request_tanggal=$ddp->tanggal;
            $newDate = date("Y-m-d", strtotime($tanggal));

            $kode_bulan=date("My", strtotime($newDate));
            // dd($kode_bulan);
            $carikode=Pembelian::where('kode_pembelian','like','%B'.$kode_bulan.'%')->orderby('id','desc')->limit(1)->get();
            // dd($carikode);
            if(count($carikode)==0){
                $kode='B'.$kode_bulan.'001';
            }else{
                foreach ($carikode as $ck){}
                $no_urut=sprintf("%03s",(int)substr($ck->kode_pembelian,-3)+1);
                $kode='B'.$kode_bulan.$no_urut;
            }

            // dd($kode);
            // $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
            $dataPembelian=[
                'kode_pembelian'=>$kode,
                'tanggal'=>$newDate,
                'supplier'=>$supplier,
                'jumlah_truk'=>$total_truk,
                'no_truk'=>$no_truk,
                'vol'=>$sum_vol,
                'total_harga'=>$sum_total,
            ];
            $dataLog=[
                'kode'=>$kode,
                'tanggal'=>$newDate,
                'supplier'=>$ddp->supplier,
                'uraian'=>$sum_vol,
                'sengon'=>$sum_Vsuper+$sum_Vsengon260+$sum_Vlog100+$sum_Vlog130+$sum_Vkalimantan,
                'harga_sengon'=>$sum_Hsuper+$sum_Hsengon260+$sum_Hlog100+$sum_Hlog130+$sum_Hkalimantan,
                'keras'=>$sum_Vkeras260+$sum_Vkeras,
                'harga_keras'=>$sum_Hkeras260+$sum_Hkeras,
                'harga'=>$sum_total,
                'ket'=>'beli',
            ];
            // dd($dataLog);
            $dataStockLogMasuk=[
                'kode'=>$kode,
                'tanggal'=>$newDate,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vsuper+$sum_Vsengon260+$sum_Vlog100+$sum_Vlog130+$sum_Vkalimantan,
                'harga'=>$sum_Hsuper+$sum_Hsengon260+$sum_Hlog100+$sum_Hlog130+$sum_Hkalimantan,
                'ket'=>'masuk',
            ];

            $dataStockLogMasukKeras=[
                'kode'=>$kode,
                'tanggal'=>$newDate,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vkeras260+$sum_Vkeras,
                'harga'=>$sum_Hkeras260+$sum_Hkeras,
                'ket'=>'masuk',
            ];

            // // dd($dataPembelian);
            // DB::beginTransaction();
            $dataUpdate=[
                'kode_pembelian'=>$kode,
                'tanggal'=>$newDate,
                'supplier'=>$supplier,
            ];
            try{
                Pembelian::create($dataPembelian);
                LogOpc::create($dataLog);
                if($sum_Vsuper+$sum_Vsengon260+$sum_Vlog100+$sum_Vlog130+$sum_Vkalimantan!=0){
                    StockLogMasuk::create($dataStockLogMasuk);
                }
                if($sum_Vkeras260+$sum_Vkeras!=0){
                    StockLogMasukKeras::create($dataStockLogMasukKeras);
                }
                DetailPembelian::where('kode_pembelian',null)->update($dataUpdate);
                DB::commit();
                return redirect("/pembelian")->with('success','Data berhasil ditambahkan!');
            }catch(Exception $e){
                DB::rollback();
                dd($e);
                return redirect("/pembelian")->with('failed','Data gagal ditambahkan!');
            }
        }else{
            return redirect("/pembelian")->with('failed','Data gagal diubah!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $de_id=Crypt::decrypt($id);
        $pembelian=Pembelian::where('id',$de_id)->get();
        foreach($pembelian as $p){}
        $kodePembelian=$p->kode_pembelian;

        $cekLog=LogOpc::where('kode',$kodePembelian)->where('status','L')->get();
        if(count($cekLog)==0){
            DB::beginTransaction();
            try{
                Pembelian::where('id',$de_id)->delete();
                LogOpc::where('kode',$kodePembelian)->delete();
                StockLogMasuk::where('kode',$kodePembelian)->delete();
                StockLogMasukKeras::where('kode',$kodePembelian)->delete();
                StockLogMasukKeras260::where('kode',$kodePembelian)->delete();
                StockLogMasukSengon260::where('kode',$kodePembelian)->delete();
                DetailPembelian::where('kode_pembelian',$kodePembelian)->delete();
                DB::commit();
                return redirect("/pembelian")->with('success','Data berhasil dihapus!');
            }catch(Exception $e){
                DB::rollback();
                dd($e);
                return redirect("/pembelian")->with('failed','Data gagal dihapus!');
            }
        }else{
            return redirect("/pembelian")->with('failed','Data gagal dihapus!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
        }
    }


    public function detail_destroy($id){
        $de_id=Crypt::decrypt($id);
        DB::beginTransaction();
        try{
            DetailPembelian::where('id',$de_id)->delete();
            DB::commit();
            return redirect()->route('pembelian.add')->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect()->route('pembelian.add')->with('failed','Data gagal dihapus!');
        }

    }
    public function detail_destroy_edit($id,$kode){
        $de_id=Crypt::decrypt($id);
        DB::beginTransaction();
        try{
            DetailPembelian::where('id',$de_id)->delete();
            DB::commit();
            return redirect('pembelian-edit/'.Crypt::encrypt($kode))->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect('pembelian-edit/'.Crypt::encrypt($kode))->with('failed','Data gagal dihapus!');
        }

    }

    public function detail_pembelian($kode){
        $kodePembelian=Crypt::decrypt($kode);
        $pembelian=Pembelian::where('kode_pembelian',$kodePembelian)->get();
        $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
            ->orderby('model','asc')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
            'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan')
            ->get();
        // dd($pembelian);
        $detail_pembelian_group=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('id_master_mentah','jenis_muatan')
            ->select('id_master_mentah','jenis_muatan')
            ->get();

        $detail_pembelian_g=DB::select("SELECT count(*),kode_pembelian,tanggal,supplier,sum(jumlah) as jumlah,sum(vol) as vol,sum(total_harga) as total_harga,detail_pembelian.id_master_mentah,id_model,detail_pembelian.harga_model,detail_pembelian.status,
            master_mentah.jenis_muatan,detail_master_mentah.kelas_model,detail_master_mentah.model,detail_master_mentah.pakem,detail_master_mentah.pakem_pembulatan
            From detail_pembelian
            join master_mentah on id_master_mentah=master_mentah.id
            join detail_master_mentah on id_model=detail_master_mentah.id
            where kode_pembelian ='$kodePembelian'
            group by kode_pembelian,tanggal,supplier,detail_pembelian.id_master_mentah,id_model,detail_pembelian.status,detail_pembelian.harga_model,
            master_mentah.jenis_muatan,detail_master_mentah.kelas_model,detail_master_mentah.model,detail_master_mentah.pakem,detail_master_mentah.pakem_pembulatan
            ");
        // $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)->get();
        return view('pembelian.detail_pembelian',compact('detail_pembelian','detail_pembelian_group','kode','pembelian'));
        // dd($detail_pembelian);
    }
    public function printPembelian($kode){
        $kodePembelian=Crypt::decrypt($kode);
        $pembelian=Pembelian::where('kode_pembelian',$kodePembelian)->get();
        $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
            ->orderby('model','asc')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
            'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan')
            ->get();
        // dd($detail_pembelian);
        foreach($detail_pembelian as $dp){}
        $supplier=$dp->supplier;
        $reg=Supplier::where('supplier',$supplier)
            ->get();
        // dd($rek);
        $detail_pembelian_group=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('id_master_mentah','jenis_muatan')
            ->select('id_master_mentah','jenis_muatan')
            ->get();

        // $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)->get();
        return view('pembelian.print_pembelian',compact('detail_pembelian','detail_pembelian_group','kode','reg','pembelian'));
        // dd($detail_pembelian);
    }

    public function detail_update_store(Request $request,$kode){
        $pembelian=Pembelian::where('kode_pembelian',$kode)->get();

        foreach($pembelian as $p){}

        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));


        $length=$request->indexLoop;
        for($i=0;$i<$length;$i++){
            $rq_model='id_model'.$i;
            $rq_afkir='afkir'.$i;
            $rq_jumlah='jumlah'.$i;
            $rq_volume='volume'.$i;
            $rq_harga='harga'.$i;
            $rq_rupiah='rupiah'.$i;
            if($request->$rq_jumlah!=null){
                $total_harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->$rq_rupiah);

                if($request->$rq_afkir=='on'){
                    $status='afkir';
                }else{
                    $status=null;
                }

                $data[]=[
                    'kode_pembelian'=>$kode,
                    'tanggal'=>$newDate,
                    'supplier'=>$request->supplier,
                    'no_truk'=>$request->no_truk,
                    'id_master_mentah'=>$request->id_master_mentah,
                    'id_model'=>$request->$rq_model,
                    'jumlah'=>$request->$rq_jumlah,
                    'vol'=>$request->$rq_volume,
                    'harga_model'=>$request->$rq_harga,
                    'total_harga'=>$total_harga,
                    'status'=>$status,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s"),
                ];
            }
        }

        $dataInfo=[
            'tanggal'=>$newDate,
            'supplier'=>$request->supplier
        ];

        // dd($data);
        DB::beginTransaction();
        try{
            // cek nama tanggal sama / beda
            if($p->nama==$request->supplier && $p->tanggal==$newDate){
                DetailPembelian::insert($data);
            }else{
                DetailPembelian::insert($data);
                DetailPembelian::where('kode_pembelian',$kode)->update($dataInfo);
                Pembelian::where('kode_pembelian',$kode)->update($dataInfo);
            }
            DB::commit();

            return redirect('pembelian-edit/'.Crypt::encrypt($kode));
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect('pembelian-edit/'.Crypt::encrypt($kode));
        }

    }

    public function getDetailpembelian(Request $request){
        $detailPembelian=DetailPembelian::whereIn('kode_pembelian',$request->kode)
            ->join('master_mentah','master_mentah.id','=','id_master_mentah')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','jenis_kayu')
            ->get();


        $arrvolS=[];
        $arrvolK=[];
        foreach ($detailPembelian as $dp) {
            $log=LogOpc::where('kode',$dp->kode_pembelian)->get();
            foreach($log as $l){}
            if($request->status=='proses1'){
                if($l->stat_sengon=='P'){
                    if($dp->jenis_kayu=='sengon'){
                        array_push($arrvolS,$dp->vol);
                    }
                }
                if($l->stat_keras=='P'){
                    if($dp->jenis_kayu=='keras'){
                        array_push($arrvolK,$dp->vol);
                    }
                }
            }else{
                if($l->stat_sengon==null){
                    if($dp->jenis_kayu=='sengon'){
                        array_push($arrvolS,$dp->vol);
                    }
                }
                if($l->stat_keras==null){
                    if($dp->jenis_kayu=='keras'){
                        array_push($arrvolK,$dp->vol);
                    }
                }
            }

        }
        $sum_Vsengon=array_sum($arrvolS);
        $sum_Vkeras=array_sum($arrvolK);

        $data=[[
            'jenis_kayu'=>'Sengon',
            'vol'=>$sum_Vsengon,
        ],[
            'jenis_kayu'=>'Keras',
            'vol'=>$sum_Vkeras,
        ]];
        return $data;
    }

    public function edit_info($id){
        $kode=Crypt::decrypt($id);
        $pembelian=Pembelian::where('kode_pembelian',$kode)->get();

        return view('pembelian.edit-info-pembelian',compact('pembelian'));
    }

    public function update_info(Request $request,$kode){
        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));
        $data=[
            'tanggal'=>$newDate,
            'supplier'=>$request->supplier,
        ];

        $datastockmentah_pro=[
            'supplier'=>$request->supplier,
        ];

        DB::beginTransaction();
        try{
            DetailPembelian::where('kode_pembelian',$kode)->update($data);
            Pembelian::where('kode_pembelian',$kode)->update($data);
            LogOpc::where('kode',$kode)->update($data);

            StockLogMk::where('kode',$kode)->update($data);
            StockLogPpc::where('kode',$kode)->update($data);
            StockLogOpc::where('kode',$kode)->update($data);

            StockLogMasuk::where('kode',$kode)->update($data);
            StockLogMasukKeras::where('kode',$kode)->update($data);
            // cek produksi
            $cariLog=LogOpc::where('kode',$kode)->where('status','L')->orwhere('status','proses')->get();
            foreach($cariLog as $cL){}
            if(count($cariLog)!=0){
                $id_log=$cL->id;

                $cariProduksi=Produksi::where('id_supplier','like','%'.$id_log.'%')->get();
                foreach($cariProduksi as $cp){
                    $id_sup=$cp->id_supplier;
                    $id_supplier=explode(",",$id_sup);
    // dd($id_supplier);
                    if(in_array($id_log,$id_supplier)==true){
                        $kodePro=$cp->kode_produksi;
                        StockLogMasuk::where('kode',$kodePro)->update($datastockmentah_pro);
                        StockLogMasukKeras::where('kode',$kodePro)->update($datastockmentah_pro);
                    }
                    $logopc=LogOpc::whereIn('id',$id_supplier)->get();
                    $nsup=[];
                    foreach($logopc as $lo){
                        array_push($nsup,$lo->supplier);

                    }
                        $nama=implode(",",$nsup);
                        // dump($nama);
                    Produksi::where('kode_produksi',$kodePro)->update(['supplier'=>$nama]);

                }
            }


            // dd('ee');
            DB::commit();

            return redirect("/pembelian")->with('success','Data berhasil diedit!');
        }catch(Exception $e){
            DB::rollback();
            dd($e);
            return redirect("/pembelian")->with('failed','Data gagal diedit!');
        }

    }
}
