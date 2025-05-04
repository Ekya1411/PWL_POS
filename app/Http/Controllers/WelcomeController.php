<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiModel;
use App\Models\TransaksiDetailModel;
use Yajra\DataTables\Facades\DataTables;

class WelcomeController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];
        $activeMenu = 'dashboard';

        $kategoriPie = TransaksiDetailModel::with('barang.kategori')
            ->selectRaw('m_barang.kategori_id, m_kategori.kategori_nama, SUM(t_penjualan_detail.jumlah) as total')
            ->join('m_barang', 't_penjualan_detail.barang_id', '=', 'm_barang.barang_id')
            ->join('m_kategori', 'm_barang.kategori_id', '=', 'm_kategori.kategori_id')
            ->groupBy('m_barang.kategori_id', 'm_kategori.kategori_nama')
            ->get();

        $totalJumlahJual = TransaksiDetailModel::
            selectRaw('SUM(jumlah) as total')
            ->first();

        return view('welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'kategoriPie' => $kategoriPie, 'totalJumlahJual' => $totalJumlahJual]); 
    }

    public function transaksiTerbaru()
    {
        $transaksi = TransaksiModel::orderBy('created_at', 'desc')->with('user')->take(5)->get();

        return DataTables::of($transaksi)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('total', function ($transaksi) {
                return 'Rp ' . number_format($transaksi->total);
            })
            ->make(true);
    }
}
