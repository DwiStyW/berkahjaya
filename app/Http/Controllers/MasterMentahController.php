<?php

namespace App\Http\Controllers;

use App\Models\HasilProduk;
use App\Models\MasterMentah;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class MasterMentahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mastermentah = MasterMentah::where('status','aktif')->get();
        return view('master-mentah.list-master-mentah',compact('mastermentah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-mentah.add-master-mentah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $data=[
                'jenis_muatan'=>$request->jenis_kayu,
                'rumus_a'=>$request->rumus_a,
                'rumus_b'=>$request->rumus_b,
                'rumus_c'=>$request->rumus_c,
                'status'=>'aktif',
            ];
        // dd($id);
        try{
            MasterMentah::create($data);
            return redirect("/mastermentah")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/mastermentah")->with('failed','Data gagal ditambahkan!');
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
        $mastermentah=MasterMentah::where('id',$de_id)->get();

        return view('master-mentah.edit-master-mentah',compact('mastermentah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $de_id=Crypt::decrypt($id);
        $data=[
                'jenis_muatan'=>$request->jenis_kayu,
                'rumus_a'=>$request->rumus_a,
                'rumus_b'=>$request->rumus_b,
                'rumus_c'=>$request->rumus_c,
                'status'=>'aktif',
            ];
        // dd($id);
        try{
            MasterMentah::where('id',$de_id)->update($data);
            return redirect("/mastermentah")->with('success','Data berhasil diedit!');
        }catch(Exception $e){
            dd($e);
            return redirect("/mastermentah")->with('failed','Data gagal diedit!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        // dd($id);
        $de_id=Crypt::decrypt($id);
        $data=[
            'status'=>'nonaktif',
        ];
        try{
            MasterMentah::where('id',$de_id)->update($data);
            return redirect("/mastermentah")->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            return redirect("/mastermentah")->with('failed','Data gagal dihapus!');
        }
    }

}