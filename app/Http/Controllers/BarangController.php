<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    //

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Data Barang']
        ];

        $page = (object) [
            'title' => 'Daftar Barang yang terdaftar pada sistem',
        ];

        $activeMenu = 'barang';

        $kategori = KategoriModel::all();

        return view('data_barang.daftar_barang', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }

    public function listBarang(Request $request)
    {
        $barang = BarangModel::with('kategori')
            ->select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');

        if ($request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }
        return DataTables::of($barang)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori ? $barang->kategori->kategori_nama : '-';
            })
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/barang/' . $barang->barang_id . '/delete') . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function edit(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Barang',
        ];

        $activeMenu = 'barang';

        return view('data_barang.edit_barang', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang, 'kategori' => $kategori]);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Barang Baru',
        ];

        $kategori = KategoriModel::all();

        $activeMenu = 'barang';

        return view('data_barang.create_barang', compact('breadcrumb', 'kategori', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|integer|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|min:3',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli', // harga jual tidak boleh lebih kecil dari harga beli
        ]);

        BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect('/barang/data_barang')->with('success', 'Data barang berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
{
    $request->validate([
        'kategori_id' => 'required|integer|exists:m_kategori,kategori_id',
        'barang_kode' => [
            'required',
            'string',
            'min:3',
            Rule::unique('m_barang', 'barang_kode')->ignore($id, 'barang_id')
        ],
        'barang_nama' => 'required|string|min:3',
        'harga_beli' => 'required|numeric|min:0',
        'harga_jual' => 'required|numeric|min:0|gte:harga_beli', // harga jual tidak boleh lebih kecil dari harga beli
    ]);

    $barang = BarangModel::findOrFail($id);
    $barang->update([
        'kategori_id' => $request->kategori_id,
        'barang_kode' => $request->barang_kode,
        'barang_nama' => $request->barang_nama,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
    ]);

    return redirect('/barang/data_barang')->with('success', 'Data barang berhasil diperbarui');
}

public function destroy(string $id)
{
    $check = BarangModel::find($id);
    if (!$check) {
        return redirect('/barang/data_barang')->with('error', 'Data user tidak ditemukan');
    }
    try {
        BarangModel::destroy($id);
        return redirect('/barang/data_barang')->with('success', 'Data user berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect('/barang/data_barang')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

    public function kategoriScreen()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Data Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar Kategori Barang yang terdaftar pada sistem',
        ];

        $activeMenu = 'kategori';


        return view('data_barang.daftar_kategori', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function listKategori(Request $request)
    {
        $barang = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        return DataTables::of($barang)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/barang/kategori/' . $kategori->kategori_id) . '/delete">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function createKategori()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Kategori Baru',
        ];

        $activeMenu = 'kategori';

        return view('data_barang.create_kategori', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|min:5',
        ]);

        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/barang/data_kategori')->with('success', 'Data kategori berhasil ditambahkan');
    }

    public function editKategori(string $id)
    {
        $kategori = KategoriModel::find($id);
        $level = KategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit kategori',
            'list' => ['Home', 'kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit kategori',
        ];

        $activeMenu = 'kategori';

        return view('data_barang.edit_kategori', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    public function updateKategori(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => [
                'required',
                'string',
                'min:3',
                Rule::unique('m_kategori', 'kategori_kode')->ignore($id, 'kategori_id')
            ],
            'kategori_nama' => 'required|min:5',
        ]);

        $kategori = KategoriModel::findOrFail($id);
        $kategori->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/barang/data_kategori')->with('success', 'Data kategori berhasil diubah');
    }

    public function destroyKategori(string $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            return redirect('/barang/data_kategori')->with('error', 'Data user tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id);
            return redirect('/barang/data_kategori')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang/data_kategori')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
