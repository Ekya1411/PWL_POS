<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

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

        return view('stok.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $stok = StokModel::select('stok_id', 'barang_id', 'user_id', 'supplier_id', 'stok_tanggal', 'stok_jumlah')
            ->with('barang')
            ->with('supplier')
            ->with('user');

        return DataTables::of($stok)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create()
    {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_jual') // tambahin kategori_id
        ->with('kategori')
        ->get();

        $supplier = SupplierModel::select('supplier_id', 'supplier_kode','supplier_nama')->get();

        return view('stok.create', compact('barang', 'supplier'));
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'    => 'required',
                'supplier_id'    => 'required',
                'jumlah'    => 'required|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            StokModel::create([
                'barang_id'   => $request->barang_id,
                'user_id'   => auth()->user()->user_id,
                'supplier_id'   => $request->supplier_id,
                'stok_tanggal' => \Carbon\Carbon::now()->format('Y-m-d'),
                'stok_jumlah'    => $request->jumlah,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan',
            ]);
        }
    }

    public function show(string $id)
    {
        $stok = StokModel::with(['user', 'barang', 'supplier'])
            ->where('stok_id', $id)
            ->firstOrFail();

        return view('stok.show', compact('stok'));
    }

    public function edit(string $id)
    {
        $stok = StokModel::with(['user', 'barang', 'supplier'])
            ->where('penjualan_id', $id)
            ->firstOrFail();

        return view('stok.edit', compact('edit'));
    }

    public function export_excel()
    {
        $stok = StokModel::with('user', 'barang', 'supplier')
        ->select('stok_jumlah', 'stok_tanggal', 'barang_id', 'supplier_id', 'user_id') // Pastikan supplier_id ada
        ->orderBy('stok_id')
        ->get();
    
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Penerima');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Tanggal');

        $sheet->getStyle('F1');
        $no = 1;
        $baris = 2;

        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('C' . $baris, $value->supplier->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->user->nama);
            $sheet->setCellValue('E' . $baris, $value->stok_jumlah);
            $sheet->setCellValue('F' . $baris, $value->stok_tanggal);
            $baris++;
            $no++;
        }
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $sheet->setTitle('Data Stok'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $stok = StokModel::with('user', 'barang', 'supplier')
        ->select('stok_jumlah', 'stok_tanggal', 'barang_id', 'supplier_id', 'user_id') // Pastikan supplier_id ada
        ->orderBy('stok_id')
        ->get();

        $pdf = Pdf::loadView('stok.export_pdf', compact('stok'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(["isRemoteEnabled" => true]);
        $pdf->render();

        return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
