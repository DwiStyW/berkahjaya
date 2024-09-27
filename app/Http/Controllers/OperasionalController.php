<?php

namespace App\Http\Controllers;

use App\Models\Operasional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operasional=Operasional::orderby('id','desc')->get();
        return view('operasional.list-operasional',compact('operasional'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operasional.add-operasional');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));

            $data=[
                'tanggal'=>$newDate,
                'uraian'=>$request->uraian,
                'kategori'=>$request->kategori,
                'harga'=>$request->total,
            ];

        try{
            Operasional::create($data);
            return redirect("/operasional")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/operasional")->with('failed','Data gagal ditambahkan!');
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
        $operasional=Operasional::where('id',$de_id)->get();
        foreach($operasional as $op){}
        $tanggal=$op->tanggal;
        return view('operasional.edit-operasional',compact('operasional','tanggal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $de_id=Crypt::decrypt($id);
        $request_tanggal=$request->tanggal;
        $newDate = date("Y-m-d", strtotime($request_tanggal));
            $data=[
                'tanggal'=>$newDate,
                'uraian'=>$request->uraian,
                'kategori'=>$request->kategori,
                'harga'=>$request->total,
            ];
        try{
            Operasional::where('id',$de_id)->update($data);
            return redirect("/operasional")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/operasional")->with('failed','Data gagal ditambahkan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         // dd($id);
        $de_id=Crypt::decrypt($id);

        try{
            Operasional::where('id',$de_id)->delete();
            return redirect("/operasional")->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            return redirect("/operasional")->with('failed','Data gagal dihapus!');
        }
    }
}