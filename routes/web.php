<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
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

Route::pattern('id', '[0-9]+');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu
    Route::get('/', [WelcomeController::class, 'index']);
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/list', [UserController::class, 'list']);
        Route::get('/create', [UserController::class, 'create']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']); // Menyimpan data user dengan ajax
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']); // Menampilkan halaman form tambah user ajax
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form tambah user ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menampilkan halaman form tambah user ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Menampilkan halaman form tambah user ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Menampilkan halaman form tambah user ajax
        Route::get('/{id}', [UserController::class, 'show']);
        Route::get('/{id}/edit', [UserController::class, 'edit']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });


    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); // Menampilkan halaman form tambah user ajax
        Route::post('/list', [SupplierController::class, 'list']);
        Route::get('/create', [SupplierController::class, 'create']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);
        Route::put('/{id}/update', [SupplierController::class, 'update']);
        Route::delete('/{id}/delete', [SupplierController::class, 'destroy']);
        // Ajax
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
        Route::delete('/{id}/destroy_ajax', [SupplierController::class, 'destroy_ajax']);
    });

    Route::group(['prefix' => 'level'], function () {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']);
        Route::get('/create', [LevelController::class, 'create']);
        Route::post('/', [LevelController::class, 'store']);
        Route::get('/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/{id}/update', [LevelController::class, 'update']);
        Route::delete('/{id}/delete', [LevelController::class, 'destroy']);
        // Ajax
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/destroy_ajax', [LevelController::class, 'destroy_ajax']);
    });

    Route::group(['prefix' => 'barang'], function () {
        //barang
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list', [BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']);
        Route::post('/store', [BarangController::class, 'store']);
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}/update', [BarangController::class, 'update']);
        Route::delete('/{id}/delete', [BarangController::class, 'destroy']);
        //ajax
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::delete('/{id}/destroy_ajax', [BarangController::class, 'destroy_ajax']);
    });

    // Kemarin jadi satu dengan barang, tapi sekarang dipisah
    Route::group(['prefix' => 'kategori'], function () {
        Route::get('/', [KategoriController::class, 'index']);
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::post('/list', [KategoriController::class, 'list']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/store_ajax', [KategoriController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
        Route::delete('/{id}/destroy_ajax', [KategoriController::class, 'destroy_ajax']);
    });

    Route::group(['prefix' => 'stok_barang'], function () {
        Route::get('/', [StokController::class, 'index']);
        Route::post('/list_stok', [StokController::class, 'listStok'])->name('stok_barang.list_stok');
    });
});