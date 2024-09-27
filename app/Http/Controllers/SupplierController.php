<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier=Supplier::orderby('id','desc')->get();
        return view('supplier.list-supplier',compact('supplier'));
    }

    public function create(){
        return view('supplier.add-supplier');
    }

    public function store(Request $request){
        $data=[
            'supplier'=>$request->supplier,
            'nama'=>$request->nama,
            'bank'=>$request->bank,
            'rek'=>$request->rek,
        ];
        try{
            Supplier::create($data);
            return redirect("/supplier")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/supplier")->with('failed','Data gagal ditambahkan!');
        }
    }

    public function edit($id){
        $de_id=Crypt::decrypt($id);
        $supplier=Supplier::where('id',$de_id)->get();
        return view('supplier.edit-supplier',compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $de_id=Crypt::decrypt($id);
        $data=[
            'supplier'=>$request->supplier,
            'nama'=>$request->nama,
            'bank'=>$request->bank,
            'rek'=>$request->rek,
        ];
        try{
            Supplier::where('id',$de_id)->update($data);
            return redirect("/supplier")->with('success','Data berhasil ditambahkan!');
        }catch(Exception $e){
            dd($e);
            return redirect("/supplier")->with('failed','Data gagal ditambahkan!');
        }
    }
    public function destroy( $id)
    {
        // dd($id);
        $de_id=Crypt::decrypt($id);

        try{
            Supplier::where('id',$de_id)->delete();
            return redirect("/supplier")->with('success','Data berhasil dihapus!');
        }catch(Exception $e){
            return redirect("/supplier")->with('failed','Data gagal dihapus!');
        }
    }
}
