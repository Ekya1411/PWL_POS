<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\WelcomeController;
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

Route::get('/', [WelcomeController::class, 'index']);
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/supplier', [UserController::class, 'daftarSupplier']);
    Route::post('/supplier_list', [UserController::class, 'supplierList']);
    Route::post('/list', [UserController::class, 'list']);
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/{id}/edit', [UserController::class, 'edit']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::delete('/supplier', [UserController::class, 'destroy']);
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);
    Route::post('/list', [LevelController::class, 'list']);
    Route::get('/create', [LevelController::class, 'create']);
    Route::post('/', [LevelController::class, 'store']);
    Route::get('/{id}/edit', [LevelController::class, 'edit']);
    Route::put('/{id}/update', [LevelController::class, 'update']);
    Route::delete('/{id}/delete', [LevelController::class, 'destroy']);
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/data_barang', [BarangController::class, 'index']);
    Route::post('/list_barang', [BarangController::class, 'listBarang']);
    Route::get('/create', [BarangController::class, 'create']);
    Route::post('/store', [BarangController::class, 'store']);
    Route::get('/{id}/edit', [BarangController::class, 'edit']);
    Route::put('/{id}/update', [BarangController::class, 'update']);
    Route::delete('/{id}/delete', [BarangController::class, 'destroy']);
    //kategori 
    Route::get('/data_kategori', [BarangController::class, 'kategoriScreen']);
    Route::post('/list_kategori', [BarangController::class, 'listKategori']);
    Route::get('/kategori/create', [BarangController::class, 'createKategori']);
    Route::post('/kategori/store', [BarangController::class, 'storeKategori']);
    Route::get('/kategori/{id}/edit', [BarangController::class, 'editKategori']);
    Route::put('/kategori/{id}/update', [BarangController::class, 'updateKategori']);
    Route::delete('/kategori/{id}/delete', [BarangController::class, 'destroyKategori']);
});

Route::group(['prefix' => 'stok_barang'], function () {
    Route::get('/', [StokController::class, 'index']);
    Route::post('/list_stok', [StokController::class, 'listStok'])->name('stok_barang.list_stok');
});