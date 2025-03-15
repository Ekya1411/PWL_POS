<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokModel;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Stok Barang Yang Tersedia',
            'list' => ['Home', 'Data Stok Barang']
        ];

        $page = (object) [
            'title' => 'Daftar Stok Barang yang terdaftar pada sistem',
        ];

        $activeMenu = 'stok';

        return view('stok_barang.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function listStok()
    {
        $stok = StokModel::select('stok_id', 'barang_id', 'stok_tanggal', 'stok_jumlah','keterangan')->with(['barang', 'user'])->get();     

        return DataTables::of($stok)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('kategori_kode', function ($stok) {
                return $stok->barang?->kategori?->kategori_kode ?? '-';
            })
            ->addColumn('barang_kode', function ($stok) {
                return $stok->barang?->barang_kode ?? '-';
            })
            ->addColumn('barang_nama', function ($stok) {
                return $stok->barang?->barang_nama ?? '-';
            })
            ->addColumn('nama', function ($stok) {
                return $stok->user?->nama ?? '-';
            })
            ->addColumn('stok_jumlah', function ($stok) {
                return $stok->stok_jumlah ?? '0';
            })
            ->addColumn('stok_tanggal', function ($stok) {
                return $stok->stok_tanggal ?? '-';
            })            
            ->addColumn('aksi', function ($stok) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/stok/' . $stok->stok_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
}
