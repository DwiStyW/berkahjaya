<?php

namespace App\Http\Controllers;

use App\Models\DetailMasterMentah;
use App\Models\DetailPembelian;
use App\Models\LogOpc;
use App\Models\MasterMentah;
use App\Models\Pembelian;
use App\Models\StockLogMasuk;
use App\Models\StockLogMasukKeras;
use App\Models\StockLogMasukKeras260;
use App\Models\StockLogMasukSengon260;
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
        $mastermentah=MasterMentah::whereIn('id',[1,2,3,4])->get();
        $detail_pembelian=DetailPembelian::where('kode_pembelian',null)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
            ->orderby('model','asc')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
            'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan')
            ->get();
        // dd($detail_pembelian);
        $detail_pembelian_group=DetailPembelian::where('kode_pembelian',null)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('id_master_mentah','jenis_muatan')
            ->select('id_master_mentah','jenis_muatan')
            ->get();
            // dd($detail_pembelian_group);
        $supplier=Supplier::get();
        return view('pembelian.add-pembelian',compact('mastermentah','detail_pembelian','detail_pembelian_group','supplier'));
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
        $arr_vol=[];
        $arr_total=[];
        $arr_Vsuper=[];
        $arr_Vsengon260=[];
        $arr_Vkeras260=[];
        $arr_Vkeras=[];
        $arr_Hsuper=[];
        $arr_Hsengon260=[];
        $arr_Hkeras260=[];
        $arr_Hkeras=[];
        foreach($data_detail_pembelian as $ddp){
            array_push($arr_vol,$ddp->vol);
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
            }
        }
        $sum_vol=array_sum($arr_vol);
        $sum_total=array_sum($arr_total);
        $sum_Vsuper=array_sum($arr_Vsuper);
        $sum_Vsengon260=array_sum($arr_Vsengon260);
        $sum_Vkeras260=array_sum($arr_Vkeras260);
        $sum_Vkeras=array_sum($arr_Vkeras);
        $sum_Hsuper=array_sum($arr_Hsuper);
        $sum_Hsengon260=array_sum($arr_Hsengon260);
        $sum_Hkeras260=array_sum($arr_Hkeras260);
        $sum_Hkeras=array_sum($arr_Hkeras);

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
            'vol'=>$sum_vol,
            'total_harga'=>$sum_total,
        ];
        // dd($dataPembelian);
        $dataLog=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'uraian'=>$sum_vol,
            'harga'=>$sum_total,
            'ket'=>'beli',
        ];

        $dataStockLogMasuk=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vsuper,
            'harga'=>$sum_Hsuper,
            'ket'=>'masuk',
        ];
        $dataStockLogMasukSengon260=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vsengon260,
            'harga'=>$sum_Hsengon260,
            'ket'=>'masuk',
        ];
        $dataStockLogMasukKeras260=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vkeras260,
            'harga'=>$sum_Hkeras260,
            'ket'=>'masuk',
        ];
        $dataStockLogMasukKeras=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vkeras,
            'harga'=>$sum_Hkeras,
            'ket'=>'masuk',
        ];

        // // dd($dataPembelian);
        DB::beginTransaction();
        try{
            Pembelian::create($dataPembelian);
            LogOpc::create($dataLog);
            if($sum_Vsuper!=0){
                StockLogMasuk::create($dataStockLogMasuk);
            }
            if($sum_Vsengon260!=0){
                StockLogMasukSengon260::create($dataStockLogMasukSengon260);
            }
            if($sum_Vkeras260!=0){
                StockLogMasukKeras260::create($dataStockLogMasukKeras260);
            }
            if($sum_Vkeras!=0){
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
        $kode=Crypt::decrypt($id);
        $pembelian=Pembelian::where('kode_pembelian',$kode)->get();
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( $id)
    {
        $kode=Crypt::decrypt($id);
        $cekLog=LogOpc::where('kode',$kode)->where('status','L')->get();
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
            $arr_Hsuper=[];
            $arr_Hsengon260=[];
            $arr_Hkeras260=[];
            $arr_Hkeras=[];
            foreach($data_detail_pembelian as $ddp){
                array_push($arr_vol,$ddp->vol);
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
                }
            }
            $sum_vol=array_sum($arr_vol);
            $sum_total=array_sum($arr_total);
            $sum_Vsuper=array_sum($arr_Vsuper);
            $sum_Vsengon260=array_sum($arr_Vsengon260);
            $sum_Vkeras260=array_sum($arr_Vkeras260);
            $sum_Vkeras=array_sum($arr_Vkeras);
            $sum_Hsuper=array_sum($arr_Hsuper);
            $sum_Hsengon260=array_sum($arr_Hsengon260);
            $sum_Hkeras260=array_sum($arr_Hkeras260);
            $sum_Hkeras=array_sum($arr_Hkeras);

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
                'vol'=>$sum_vol,
                'total_harga'=>$sum_total,
            ];
            // dd($dataPembelian);
            // $dataLog=[
            //     'kode'=>$kode,
            //     'tanggal'=>$request_tanggal,
            //     'supplier'=>$ddp->supplier,
            //     'uraian'=>$sum_vol,
            //     'harga'=>$sum_total,
            //     'ket'=>'beli',
            // ];
            $dataLog=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                // 'uraian'=>$sum_Vsuper+$sum_Vkeras,
                'uraian'=>$sum_vol,
                // 'harga'=>$sum_Hsuper+$sum_Hkeras,
                'harga'=>$sum_total,
                'ket'=>'beli',
            ];
            // dd($dataLog);
            $dataStockLogMasuk=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vsuper,
                'harga'=>$sum_Hsuper,
                'ket'=>'masuk',
            ];
            $dataStockLogMasukSengon260=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vsengon260,
                'harga'=>$sum_Hsengon260,
                'ket'=>'masuk',
            ];
            $dataStockLogMasukKeras260=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vkeras260,
                'harga'=>$sum_Hkeras260,
                'ket'=>'masuk',
            ];
            $dataStockLogMasukKeras=[
                'kode'=>$kode,
                'tanggal'=>$request_tanggal,
                'supplier'=>$ddp->supplier,
                'volume'=>$sum_Vkeras,
                'harga'=>$sum_Hkeras,
                'ket'=>'masuk',
            ];

            // // dd($dataPembelian);
            // DB::beginTransaction();
            try{
                Pembelian::create($dataPembelian);
                LogOpc::create($dataLog);
                if($sum_Vsuper!=0){
                    StockLogMasuk::create($dataStockLogMasuk);
                }
                if($sum_Vsengon260!=0){
                    StockLogMasukSengon260::create($dataStockLogMasukSengon260);
                }
                if($sum_Vkeras260!=0){
                    StockLogMasukKeras260::create($dataStockLogMasukKeras260);
                }
                if($sum_Vkeras!=0){
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
        }else{
            return redirect("/pembelian")->with('failed','Data gagal diubah!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
        }
        // $pembelian=Pembelian::where('id',$de_id)->get();
        // foreach($pembelian as $p){}
        // $kodePembelian=$p->kode_pembelian;


        // if(count($cekLog)==0){
        //     $harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->harga);
        //     $dataPembelian=[
        //         'supplier'=>$request->supplier,
        //         'vol'=>$request->vol,
        //         'total_harga'=>$harga,
        //     ];
        //     $dataLog=[
        //         'supplier'=>$request->supplier,
        //         'uraian'=>$request->vol,
        //         'harga'=>$harga,
        //     ];

        //     DB::beginTransaction();
        //     try{
        //         Pembelian::where('id',$de_id)->update($dataPembelian);
        //         LogOpc::where('kode',$kodePembelian)->update($dataLog);
        //         DB::commit();
        //         return redirect("/pembelian")->with('success','Data berhasil diubah!');
        //     }catch(Exception $e){
        //         DB::rollback();
        //         dd($e);
        //         return redirect("/pembelian")->with('failed','Data gagal diubah!');
        //     }
        // }else{
        //     return redirect("/pembelian")->with('failed','Data gagal diubah!, Data telah diproduksi, hapus data produksi terlebih dahulu.!');
        // }
        // dump($dataPembelian);
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



    public function detail_pembelian($kode){
        $kodePembelian=Crypt::decrypt($kode);
        $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
            ->orderby('model','asc')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
            'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan','detail_master_mentah.harga as harga_model')
            ->get();
        // dd($detail_pembelian);
        $detail_pembelian_group=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('id_master_mentah','jenis_muatan')
            ->select('id_master_mentah','jenis_muatan')
            ->get();
        // $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)->get();
        return view('pembelian.detail_pembelian',compact('detail_pembelian','detail_pembelian_group','kode'));
        // dd($detail_pembelian);
    }
    public function printPembelian($kode){
        $kodePembelian=Crypt::decrypt($kode);
        $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->join('detail_master_mentah','id_model','=','detail_master_mentah.id')
            ->orderby('model','asc')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan','detail_master_mentah.kelas_model as kelas_model','detail_master_mentah.model as model',
            'detail_master_mentah.pakem as pakem','detail_master_mentah.pakem_pembulatan as pakem_pembulatan','detail_master_mentah.harga as harga_model')
            ->get();
        // dd($detail_pembelian);
        foreach($detail_pembelian as $dp){}
        $supplier=$dp->supplier;
        $reg=Supplier::where('supplier','like','%'.$supplier.'%')
            ->orwhere('nama','like','%'.$supplier.'%')
            ->get();
        // dd($rek);
        $detail_pembelian_group=DetailPembelian::where('kode_pembelian',$kodePembelian)
            ->join('master_mentah','id_master_mentah','=','master_mentah.id')
            ->groupby('id_master_mentah','jenis_muatan')
            ->select('id_master_mentah','jenis_muatan')
            ->get();

        // $detail_pembelian=DetailPembelian::where('kode_pembelian',$kodePembelian)->get();
        return view('pembelian.print_pembelian',compact('detail_pembelian','detail_pembelian_group','kode','reg'));
        // dd($detail_pembelian);
    }

    public function detail_update_store(Request $request,$kode){
        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));
        $total_harga=(int)preg_replace("/([^0-9\\,])/i", "", $request->total_harga);

        if($request->afkir=='on'){
            $status='afkir';
        }else{
            $status=null;
        }
// dd($status);
        $pmbl=Pembelian::where('kode_pembelian',$kode)->get();
        foreach($pmbl as $p)
        $data=[
            'kode_pembelian'=>$kode,
            'tanggal'=>$p->tanggal,
            'supplier'=>$p->supplier,
            'id_master_mentah'=>$request->id_master_mentah,
            'id_model'=>$request->id_model,
            'jumlah'=>$request->jumlah,
            'vol'=>$request->vol,
            'harga_model'=>$request->harga,
            'total_harga'=>$total_harga,
            'status'=>$status,
        ];
        // dd();
        try{
            DetailPembelian::create($data);

            return redirect('pembelian-edit/'.Crypt::encrypt($kode));
        }catch(Exception $e){
            dd($e);
            return redirect('pembelian-edit/'.Crypt::encrypt($kode));
        }

    }

    public function detail_pembelian_update($kode){
        DB::beginTransaction();
        DetailPembelian::where('kode_pembelian',$kode)->update(['kode'=>null]);
        Pembelian::where('kode_pembelian',$kode)->delete();
        LogOpc::where('kode',$kode)->delete();
        StockLogMasuk::where('kode',$kode)->delete();
        StockLogMasukKeras::where('kode',$kode)->delete();
        StockLogMasukKeras260::where('kode',$kode)->delete();
        StockLogMasukSengon260::where('kode',$kode)->delete();

        $data_detail_pembelian=DetailPembelian::where('kode_pembelian',null)->get();
        $arr_vol=[];
        $arr_total=[];
        $arr_Vsuper=[];
        $arr_Vsengon260=[];
        $arr_Vkeras260=[];
        $arr_Vkeras=[];
        $arr_Hsuper=[];
        $arr_Hsengon260=[];
        $arr_Hkeras260=[];
        $arr_Hkeras=[];
        foreach($data_detail_pembelian as $ddp){
            array_push($arr_vol,$ddp->vol);
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
            }
        }
        $sum_vol=array_sum($arr_vol);
        $sum_total=array_sum($arr_total);
        $sum_Vsuper=array_sum($arr_Vsuper);
        $sum_Vsengon260=array_sum($arr_Vsengon260);
        $sum_Vkeras260=array_sum($arr_Vkeras260);
        $sum_Vkeras=array_sum($arr_Vkeras);
        $sum_Hsuper=array_sum($arr_Hsuper);
        $sum_Hsengon260=array_sum($arr_Hsengon260);
        $sum_Hkeras260=array_sum($arr_Hkeras260);
        $sum_Hkeras=array_sum($arr_Hkeras);

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
            'vol'=>$sum_vol,
            'total_harga'=>$sum_total,
        ];
        // dd($dataPembelian);
        $dataLog=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            // 'uraian'=>$sum_Vsuper+$sum_Vkeras,
            'uraian'=>$sum_vol,
            // 'harga'=>$sum_Hsuper+$sum_Hkeras,
            'harga'=>$sum_total,
            'ket'=>'beli',
        ];
        // dd($dataLog);
        $dataStockLogMasuk=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vsuper,
            'harga'=>$sum_Hsuper,
            'ket'=>'masuk',
        ];
        $dataStockLogMasukSengon260=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vsengon260,
            'harga'=>$sum_Hsengon260,
            'ket'=>'masuk',
        ];
        $dataStockLogMasukKeras260=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vkeras260,
            'harga'=>$sum_Hkeras260,
            'ket'=>'masuk',
        ];
        $dataStockLogMasukKeras=[
            'kode'=>$kode,
            'tanggal'=>$request_tanggal,
            'supplier'=>$ddp->supplier,
            'volume'=>$sum_Vkeras,
            'harga'=>$sum_Hkeras,
            'ket'=>'masuk',
        ];

        // // dd($dataPembelian);
        DB::beginTransaction();
        try{
            Pembelian::create($dataPembelian);
            LogOpc::create($dataLog);
            if($sum_Vsuper!=0){
                StockLogMasuk::create($dataStockLogMasuk);
            }
            if($sum_Vsengon260!=0){
                StockLogMasukSengon260::create($dataStockLogMasukSengon260);
            }
            if($sum_Vkeras260!=0){
                StockLogMasukKeras260::create($dataStockLogMasukKeras260);
            }
            if($sum_Vkeras!=0){
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
    public function getDetailpembelian(Request $requset){
        $detailPembelian=DetailPembelian::whereIn('kode_pembelian',$requset->kode)
            // ->where('id_master_mentah','!=',[2,3])
            ->join('master_mentah','master_mentah.id','=','id_master_mentah')
            ->select('detail_pembelian.*','master_mentah.jenis_muatan as jenis_muatan')
            ->get();
        return $detailPembelian;
    }
}