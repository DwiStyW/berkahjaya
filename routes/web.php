<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailMasterMentahController;
use App\Http\Controllers\HasilProdukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogopcController;
use App\Http\Controllers\MasterMentahController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // dashboard
    // Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // hasil produk
    Route::get('/hasilproduk', [HasilProdukController::class, 'index'])->name('hasilproduk.index');
    Route::get('/hasilproduk-add', [HasilProdukController::class, 'create'])->name('hasilproduk.add');
    Route::post('/hasilproduk-store', [HasilProdukController::class, 'store'])->name('hasilproduk.store');
    Route::get('/hasilproduk-edit/{id}', [HasilProdukController::class, 'edit'])->name('hasilproduk.edit');
    Route::post('/hasilproduk-update/{id}', [HasilProdukController::class, 'update'])->name('hasilproduk.update');
    Route::get('/hasilproduk-delete/{id}', [HasilProdukController::class, 'destroy'])->name('hasilproduk.delete');

    // master mentah
    Route::get('/mastermentah', [MasterMentahController::class, 'index'])->name('mastermentah.index');
    Route::get('/mastermentah-add', [MasterMentahController::class, 'create'])->name('mastermentah.add');
    Route::post('/mastermentah-store', [MasterMentahController::class, 'store'])->name('mastermentah.store');
    Route::get('/mastermentah-edit/{id}', [MasterMentahController::class, 'edit'])->name('mastermentah.edit');
    Route::post('/mastermentah-update/{id}', [MasterMentahController::class, 'update'])->name('mastermentah.update');
    Route::get('/mastermentah-delete/{id}', [MasterMentahController::class, 'destroy'])->name('mastermentah.delete');

    // detail master mentah
    Route::get('/detailmastermentah/{id}', [DetailMasterMentahController::class, 'index'])->name('detailmastermentah.index');
    Route::get('/detailmastermentah-add/{id}', [DetailMasterMentahController::class, 'create'])->name('detailmastermentah.add');
    Route::post('/detailmastermentah-store', [DetailMasterMentahController::class, 'store'])->name('detailmastermentah.store');
    Route::get('/detailmastermentah-edit/{id}', [DetailMasterMentahController::class, 'edit'])->name('detailmastermentah.edit');
    Route::post('/detailmastermentah-update/{id}', [DetailMasterMentahController::class, 'update'])->name('detailmastermentah.update');
    Route::get('/detailmastermentah-delete/{id}', [DetailMasterMentahController::class, 'destroy'])->name('detailmastermentah.delete');

    // produksi
    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
    Route::get('/produksi-add', [ProduksiController::class, 'create'])->name('produksi.add');
    // Route::get('/produksi-perkode-add/{kode}', [ProduksiController::class, 'createPerkode'])->name('produksi_perkode.add');
    Route::post('/produksi-temporary', [ProduksiController::class, 'temporary'])->name('produksi.temporary');
    Route::post('/produksi-temporary-perkode/{kode}', [ProduksiController::class, 'temporary_perkode'])->name('produksi.temporary_perkode');
    Route::get('/produksi-store', [ProduksiController::class, 'store'])->name('produksi.store');
    Route::get('/produksi-perkode-store/{kode}', [ProduksiController::class, 'store_perkode'])->name('produksi.store_perkode');
    Route::get('/produksi-edit-perkode/{id}', [ProduksiController::class, 'createPerkode'])->name('produksi.edit_perkode');
    // Route::get('/produksi-edit-perkode/{id}', [ProduksiController::class, 'edit_perkode'])->name('produksi.edit_perkode');
    Route::get('/produksi-edit/{id}', [ProduksiController::class, 'edit'])->name('produksi.edit');
    Route::post('/produksi-update/{id}', [ProduksiController::class, 'update'])->name('produksi.update');
    Route::get('/produksi-delete/{id}', [ProduksiController::class, 'destroy'])->name('produksi.delete');
    Route::get('/produksi-temporary-delete/{id}', [ProduksiController::class, 'destroy_temporary'])->name('produksi.delete_temporary');
    Route::get('/produksi-temporary-perkode-delete/{id}/{kode}', [ProduksiController::class, 'destroy_temporary_perkode'])->name('produksi.delete_temporary_perkode');


    // logopc
    Route::get('/logopc', [LogopcController::class, 'index'])->name('logopc.index');
    Route::get('/logopc-add', [LogopcController::class, 'create'])->name('logopc.add');
    Route::post('/logopc-store', [LogopcController::class, 'store'])->name('logopc.store');
    Route::get('/logopc-edit/{id}', [LogopcController::class, 'edit'])->name('logopc.edit');
    Route::post('/logopc-pembelian-update/{id}', [LogopcController::class, 'updatePembelian'])->name('logopc.update_pembelian');
    Route::post('/logopc-penjualan-update/{id}', [LogopcController::class, 'updatePenjualan'])->name('logopc.update_penjualan');
    Route::post('/logopc-stock-update/{id}', [LogopcController::class, 'updateStock'])->name('logopc.update_stock');
    Route::get('/logopc-delete/{id}', [LogopcController::class, 'destroy'])->name('logopc.delete');

        // pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian-detail/{id}', [PembelianController::class, 'detail_pembelian'])->name('pembelian.detail');
    Route::get('/printPembelian/{id}', [PembelianController::class, 'printPembelian'])->name('pembelian.print');
    Route::get('/pembelian-add', [PembelianController::class, 'create'])->name('pembelian.add');
    Route::get('/pembelian-store', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('/pembelian-edit/{id}', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::get('/pembelian-update/{id}', [PembelianController::class, 'update'])->name('pembelian.update');
    Route::get('/pembelian-delete/{id}', [PembelianController::class, 'destroy'])->name('pembelian.delete');
    Route::get('/detailpembelian-delete/{id}', [PembelianController::class, 'detail_destroy'])->name('detailpembelian.delete');
    Route::post('/getModel', [PembelianController::class, 'getModel'])->name('getModel');
    Route::post('/getDetailmodel', [PembelianController::class, 'getDetailmodel'])->name('getDetailmodel');
    Route::post('/detailpembelian-store', [PembelianController::class, 'detail_store'])->name('detailpembelian.store');
    Route::post('/detailpembelian-perkode-edit/{id}', [PembelianController::class, 'detail_update_store'])->name('detailpembelian.update');
    Route::post('/getDetailpembelian', [PembelianController::class, 'getDetailpembelian'])->name('getDetailpembelian');

        // penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan-detail/{id}', [PenjualanController::class, 'detail_penjualan'])->name('penjualan.detail');
    Route::get('/printPenjualan/{id}', [PenjualanController::class, 'printPenjualan'])->name('penjualan.detail');
    Route::get('/penjualan-add', [PenjualanController::class, 'create'])->name('penjualan.add');
    Route::post('/penjualan-store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan-edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::post('/penjualan-update/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::get('/penjualan-delete/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.delete');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // stock
    Route::get('/stockbaku', [StockController::class, 'stock_baku'])->name('stockbaku');
    Route::get('/stockmasuk', [StockController::class, 'stock_masuk'])->name('stockmasuk');
    Route::get('/stockmasukkeras', [StockController::class, 'stock_masuk_keras'])->name('stockmasukkeras');
    Route::get('/stockopc', [StockController::class, 'stock_opc'])->name('stockopc');
    Route::get('/stockppc', [StockController::class, 'stock_ppc'])->name('stockppc');
    Route::get('/stockmk', [StockController::class, 'stock_mk'])->name('stockmk');
});

require __DIR__.'/auth.php';