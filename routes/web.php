<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\RiwayatTransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[AuthController::class,'loginForm']);
Route::post('/login',[AuthController::class,'login'])->name('auth.login');


Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
Route::get('/produk',[ProdukController::class,'index'])->name('produk');
Route::get('/kategori',[KategoriController::class,'index'])->name('kategori');
Route::get('/transaksi',[TransaksiController::class,'index'])->name('transaksi');
Route::get('/riwayat-transaksi',[RiwayatTransaksiController::class,'index'])->name('riwayat-transaksi');


Route::get('/produk/create', [ProdukController::class, 'createProduk'])->name('produk.create');
Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
Route::get('/produk/editProduk/{id}', [ProdukController::class, 'editProduk'])->name('produk.edit');
Route::put('/produk/updateProduk/{id}', [ProdukController::class, 'updateProduk'])->name('produk.update');
Route::delete('/produk/delete/{id}', [ProdukController::class, 'hapusProduk'])->name('produk.delete');

Route::get('/kategori/create', [KategoriController::class, 'createKategori'])->name('kategori.create');
Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
Route::get('/kategori/editKategori/{id}', [KategoriController::class, 'editKategori'])->name('kategori.edit');
Route::put('/kategori/updateKategori/{id}', [KategoriController::class, 'updateKategori'])->name('kategori.update');
Route::delete('/kategori/delete/{id}', [KategoriController::class, 'hapusKategori'])->name('kategori.delete');

Route::get('/transaksi/editTransaksi/{id}', [TransaksiController::class, 'editTransaksi'])->name('transaksi.edit');
Route::put('/transaksi/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
Route::delete('/transaksi/delete/{id}', [TransaksiController::class, 'hapusTransaksi'])->name('transaksi.delete');





