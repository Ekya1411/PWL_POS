<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ChartController;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Monolog\Level;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

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

Route::get('account/create', [UserController::class, 'create']);
Route::post('account/store', [UserController::class, 'register']);

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu
    Route::get('/', [WelcomeController::class, 'index']);
    Route::post('/welcome/transaksi_terbaru', [WelcomeController::class, 'transaksiTerbaru']);

    Route::get('/profile', [UserController::class, 'get_profile_pic']);
    Route::get('/upload_profile', [UserController::class, 'upload_profile_pic']);
    Route::post('/store_profile', [UserController::class, 'store_profile_pic']);

    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/list', [UserController::class, 'list']);
        // Di pindah di atas sendiri untuk registrasi user
        // Route::get('/create', [UserController::class, 'create']);
        // Route::post('/', [UserController::class, 'store']);
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

        Route::get('/import', [UserController::class, 'import']);
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    });


    Route::group(['prefix' => 'supplier', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
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

        Route::get('/import', [SupplierController::class, 'import']);
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/export_excel', [SupplierController::class, 'export_excel']);
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
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

        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelController::class, 'export_excel']);
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'barang', 'middleware' => 'authorize:ADM,MNG'], function () {
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

        Route::get('/import', [BarangController::class, 'import']);
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
        Route::get('/export_excel', [BarangController::class, 'export_excel']);
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']);
    });

    // Kemarin jadi satu dengan barang, tapi sekarang dipisah
    Route::group(['prefix' => 'kategori', 'middleware' => 'authorize:ADM,MNG'], function () {
        Route::get('/', [KategoriController::class, 'index']);
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::post('/list', [KategoriController::class, 'list']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/store_ajax', [KategoriController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
        Route::delete('/{id}/destroy_ajax', [KategoriController::class, 'destroy_ajax']);

        Route::get('/import', [KategoriController::class, 'import']);
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
        Route::get('/export_excel', [KategoriController::class, 'export_excel']);
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'stok', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [StokController::class, 'index']);
        Route::post('/list', [StokController::class, 'list']);
        Route::get('/{id}/show', [StokController::class, 'show']);
        Route::get('/create', [StokController::class, 'create']);
        Route::post('/store', [StokController::class, 'store']);
        Route::get('/{id}/edit', [StokController::class, 'edit']);
        Route::post('/{id}/update', [StokController::class, 'update']);
        Route::get('/{id}/delete', [StokController::class, 'delete']);
        Route::delete('/{id}/destroy', [StokController::class, 'destroy']);

        Route::get('/export_excel', [StokController::class, 'export_excel']);
        Route::get('/export_pdf', [StokController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'transaksi', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [TransaksiController::class, 'index']);
        Route::post('/list', [TransaksiController::class, 'list']);
        Route::get('/{id}/show', [TransaksiController::class, 'show']);
        Route::get('/create', [TransaksiController::class, 'create']);
        Route::post('/store', [TransaksiController::class, 'store']);
        Route::get('/{id}/edit', [TransaksiController::class, 'edit']);
        Route::post('/{id}/update', [TransaksiController::class, 'update']);
        Route::get('/{id}/delete', [TransaksiController::class, 'delete']);
        Route::delete('/{id}/destroy', [TransaksiController::class, 'destroy']);

        Route::get('/export_excel', [TransaksiController::class, 'export_excel']);
        Route::get('/export_pdf', [TransaksiController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'chart'], function () {
        Route::get('/data', [ChartController::class, 'chartData']);
    });
});
