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

    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePembelian(Request $request, $id)
    {

    }
    public function updatePenjualan(Request $request, $id)
    {

    }
    public function updateStock(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
