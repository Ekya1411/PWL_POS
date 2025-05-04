<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

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

        return view('barang.index', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }

    public function list(Request $request)
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
            ->addColumn('stok', function ($barang) {
                // Memanggil metode getTotalStok() untuk mendapatkan total stok yang tersedia
                return $barang->getTotalStok();  // Panggil getTotalStok sebagai metode
            })                      
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
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

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang, 'kategori' => $kategori]);
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

        return view('barang.create', compact('breadcrumb', 'kategori', 'page', 'activeMenu'));
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

        return redirect('/barang/')->with('success', 'Data barang berhasil ditambahkan');
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

        return redirect('/barang/')->with('success', 'Data barang berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect('/barang/')->with('error', 'Data user tidak ditemukan');
        }
        try {
            BarangModel::destroy($id);
            return redirect('/barang/')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang/')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        $kategori = KategoriModel::all();

        return view('barang.create_ajax', compact('kategori'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id'   => 'required|exists:m_kategori,kategori_id',
                'barang_kode'   => 'required|string|min:3|max:6|unique:m_barang,barang_kode',
                'barang_nama'   => 'required|string|min:3|max:50|unique:m_barang,barang_nama',
                'harga_beli'    => 'required|numeric|min:0',
                'harga_jual'    => 'required|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            BarangModel::create([
                'kategori_id'   => $request->kategori_id,
                'barang_kode'   => $request->barang_kode,
                'barang_nama'   => $request->barang_nama,
                'harga_beli'    => $request->harga_beli,
                'harga_jual'    => $request->harga_jual,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan',
            ]);
        }

        return redirect('/barang/');
    }

    public function show_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        return view('barang.show_ajax', compact('barang'));
    }

    public function edit_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        return view('barang.edit_ajax', compact('barang', 'kategori'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id'   => 'required|exists:m_kategori,kategori_id',
                'barang_kode'   => 'required|string|min:3|max:6|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama'   => 'required|string|min:3|max:50|unique:m_barang,barang_nama,' . $id . ',barang_id',
                'harga_beli'    => 'required|numeric|min:0',
                'harga_jual'    => 'required|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $barang = BarangModel::find($id);
            if (!$barang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            $barang->update([
                'kategori_id'   => $request->kategori_id,
                'barang_kode'   => $request->barang_kode,
                'barang_nama'   => $request->barang_nama,
                'harga_beli'    => $request->harga_beli,
                'harga_jual'    => $request->harga_jual,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil diubah',
            ]);
        }

        return redirect('/barang/');
    }


    public function confirm_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', compact('barang'));
    }

    public function destroy_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);

            if (!$barang) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data barang tidak ditemukan',
                ]);
            }

            try {
                $barang->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil dihapus',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini',
                ]);
            }
        }

        return redirect('/barang');
    }

    public function import()
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_barang'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->rangeToArray(
                'A1:E' . $sheet->getHighestRow(),
                null,
                true,
                false
            );
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 0) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'kategori_id' => $value[0],
                            'barang_kode' => $value[1],
                            'barang_nama' => $value[2],
                            'harga_beli' => $value[3],
                            'harga_jual' => $value[4],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    BarangModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/barang');
    }

    public function export_excel()
    {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('F1', 'Kategori');
        $no = 1;
        $baris = 2;

        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang_kode);
            $sheet->setCellValue('C' . $baris, $value->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->harga_beli);
            $sheet->setCellValue('E' . $baris, $value->harga_jual);
            $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $sheet->setTitle('Data Barang'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Barang ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d MY H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->orderBy('barang_kode')
            ->with('kategori')
            ->get();

        $pdf = Pdf::loadView('barang.export_pdf', compact('barang'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(["isRemoteEnabled" => true]);
        $pdf->render();

        return $pdf->stream('Data Barang ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
