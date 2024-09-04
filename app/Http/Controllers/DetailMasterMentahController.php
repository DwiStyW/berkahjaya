<?php

namespace App\Http\Controllers;

use App\Models\DetailMasterMentah;
use App\Models\HasilProduk;
use App\Models\MasterMentah;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class DetailMasterMentahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $de_id=Crypt::decrypt($id);
        $detail_mastermentah = DetailMasterMentah::where('id_master_mentah',$de_id)->get();
        return view('detail-master-mentah.list-detail-master-mentah',compact('detail_mastermentah','id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $de_id=Crypt::decrypt($id);
        $mastermentah=MasterMentah::where('id',$de_id)->get();
        return view('detail-master-mentah.add-detail-master-mentah',compact('mastermentah','de_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $en_id=Crypt::encrypt($request->id_master);
        $data=[
                'id_master_mentah'=>$request->id_master,
                'kelas_model'=>$request->kelas_model,
                'model'=>$request->model,
                'pakem'=>$request->pakem,
                'pakem_pembulatan'=>$request->pakem_pembulatan,
                'harga'=>$request->harga,
            ];
        // dd($data);
        try{
            DetailMasterMentah::create($data);
            return redirect("/detailmastermentah/".$en_id)->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/detailmastermentah/".$en_id)->with('failed','Data gagal ditambahkan!');
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
        $detailmastermentah=DetailMasterMentah::where('id',$de_id)->get();
        foreach($detailmastermentah as $item){
            $id_master=$item->id_master_mentah;
        }
        $mastermentah=MasterMentah::where('id',$id_master)->get();
        return view('detail-master-mentah.edit-detail-master-mentah',compact('detailmastermentah','id_master','mastermentah'));
    }

     public function edit_perkelas($id,$kelas)
    {
        // dd($id,$kode);
        if($kelas=='null'){
            $kls=null;
        }else{
            $kls=$kelas;
        }
        $detailmastermentah=DetailMasterMentah::where('id_master_mentah',$id)->where('kelas_model',$kls)->get();
        // dd($detailmastermentah);
        return view('detail-master-mentah.edit-detail-master-mentah-permodel',compact('detailmastermentah','id','kelas'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $de_id=Crypt::decrypt($id);
        $data=[
                'id_master_mentah'=>$request->id_master,
                'kelas_model'=>$request->kelas_model,
                'model'=>$request->model,
                'pakem'=>$request->pakem,
                'pakem_pembulatan'=>$request->pakem_pembulatan,
                'harga'=>$request->harga,
                'harga_khusus'=>$request->harga_khusus,
            ];
        // dd($data);
        $en_id=Crypt::encrypt($request->id_master);
        try{
            DetailMasterMentah::where('id',$de_id)->update($data);
            return redirect("/detailmastermentah/".$en_id)->with('success','Data berhasil diedit!');
        }catch(Exception $e){
            dd($e);
            return redirect("/detailmastermentah/".$en_id)->with('failed','Data gagal diedit!');
        }
    }
    public function update_perkelas(Request $request, $id,$kelas)
    {

        $data=[
                'harga'=>$request->harga,
                'harga_khusus'=>$request->harga_khusus,
            ];
        // dd($id,$kelas);
        if($kelas=='null'){
            $kls=null;
        }else{
            $kls=$kelas;
        }

        $en_id=Crypt::encrypt($id);
        try{
            DetailMasterMentah::where('id_master_mentah',$id)->where('kelas_model',$kls)->update($data);
            return redirect("/detailmastermentah/".$en_id)->with('success','Data berhasil diedit!');
        }catch(Exception $e){
            dd($e);
            return redirect("/detailmastermentah/".$en_id)->with('failed','Data gagal diedit!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        // dd($id);
        $de_id=Crypt::decrypt($id);
        $detailmastermentah=DetailMasterMentah::where('id',$de_id)->get();
        foreach($detailmastermentah as $item){
            $id_master=$item->id_master_mentah;
        }
        $en_id=Crypt::encrypt($id_master);
        try{
            DetailMasterMentah::where('id',$de_id)->delete();
            return redirect("/detailmastermentah/".$en_id)->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            return redirect("/detailmastermentah/".$en_id)->with('failed','Data gagal dihapus!');
        }
    }

}
