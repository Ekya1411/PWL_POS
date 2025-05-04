<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransaksiModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\TransaksiDetailModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TransaksiController extends Controller
{
    //
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Transaksi',
            'list' => ['Home', 'Data Transaksi']
        ];

        $page = (object) [
            'title' => 'Data transaksi yang terdaftar pada sistem',
        ];

        $activeMenu = 'transaksi';

        return view('transaksi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $transaksi = TransaksiModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        return DataTables::of($transaksi)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('total', function ($transaksi) {
                return 'Rp ' . number_format($transaksi->total);
            })
            ->addColumn('aksi', function ($transaksi) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/transaksi/' . $transaksi->penjualan_id .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/transaksi/' . $transaksi->penjualan_id .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/transaksi/' . $transaksi->penjualan_id .
                    '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function show(string $id)
    {
        $transaksi = TransaksiModel::with(['user', 'detail_transaksi.barang'])
            ->where('penjualan_id', $id)
            ->firstOrFail();

        return view('transaksi.show', compact('transaksi'));
    }

    public function create()
    {
        $lastKode = TransaksiModel::select('penjualan_kode')
            ->orderBy('penjualan_kode', 'desc')
            ->value('penjualan_kode');

        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')
            ->orderBy('kategori_nama', 'asc')
            ->get();

        // Ambil angka terakhir dari kode, contoh PJ-0009 jadi 9
        $lastNumber = $lastKode ? (int)substr($lastKode, 3) : 0;

        // Generate kode baru, dengan format PJ-xxxx
        $newKode = 'PJ-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_jual') // tambahin kategori_id
            ->with('kategori')
            ->get();

        return view('transaksi.create', compact('newKode', 'barang', 'kategori'));
    }

    public function store(Request $request)
    {
        try {
            // Basic validation
            $validator = Validator::make($request->all(), [
                'barangData' => 'required',
                'pembeli' => 'required|string|min:3|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal!',
                    'msgField' => $validator->errors()
                ]);
            }

            // Parse barang data from the hidden input
            $barangData = json_decode($request->input('barangData'), true);

            if (empty($barangData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada barang yang dipilih!',
                ]);
            }

            // Generate new transaction code
            $lastKode = TransaksiModel::select('penjualan_kode')
                ->orderBy('penjualan_kode', 'desc')
                ->value('penjualan_kode');

            // Extract last number from code, e.g., PJ-0009 to 9
            $lastNumber = $lastKode ? (int)substr($lastKode, 3) : 0;

            // Generate new code with format PJ-xxxx
            $newKode = 'PJ-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            // Create transaction header
            $transaksi = TransaksiModel::create([
                'user_id' => auth()->user()->user_id,
                'pembeli' => $request->input('pembeli'),
                'penjualan_kode' => $newKode,
                'penjualan_tanggal' => Carbon::now()->format('Y-m-d H:i:s'),
                // Add other columns as needed based on your table structure
            ]);

            // Add transaction details
            foreach ($barangData as $id => $item) {
                TransaksiDetailModel::create([
                    'penjualan_id' => $transaksi->penjualan_id,
                    'barang_id' => $id,
                    'harga' => $item['harga'] * $item['jumlah'],
                    'jumlah' => $item['jumlah'],
                ]);

                // Update stock if needed
                // BarangModel::where('barang_id', $id)->decrement('stok', $item['jumlah']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        // Get transaction data
        $transaksi = TransaksiModel::with('detail_transaksi')->findOrFail($id);

        // Get categories for the filter dropdown
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')
            ->orderBy('kategori_nama', 'asc')
            ->get();

        // Get all products
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_jual')
            ->with('kategori')
            ->get();

        // Get current transaction code
        $newKode = $transaksi->penjualan_kode;

        // Format transaction date for the date input
        $tanggal = date('Y-m-d', strtotime($transaksi->penjualan_tanggal));

        // Return the edit view with data
        return view('transaksi.edit', compact('transaksi', 'newKode', 'barang', 'kategori', 'tanggal'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Basic validation
            $validator = Validator::make($request->all(), [
                'barangData' => 'required',
                'pembeli' => 'required|string|min:3|max:50',
                'tanggal' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal!',
                    'msgField' => $validator->errors()
                ]);
            }

            // Parse barang data from the hidden input
            $barangData = json_decode($request->input('barangData'), true);

            if (empty($barangData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada barang yang dipilih!',
                ]);
            }

            // Find the transaction
            $transaksi = TransaksiModel::findOrFail($id);

            // Calculate total amount
            $totalAmount = 0;
            foreach ($barangData as $item) {
                $totalAmount += ($item['harga'] * $item['jumlah']);
            }

            // Update transaction header
            $transaksi->update([
                'user_id' => auth()->user()->user_id,
                'pembeli' => $request->input('pembeli'),
                'penjualan_tanggal' => $request->input('tanggal') . ' ' . date('H:i:s'),
                'total_harga' => $totalAmount,
                'updated_at' => Carbon::now()
            ]);

            // Delete existing details
            TransaksiDetailModel::where('penjualan_id', $id)->delete();

            // Add updated transaction details
            foreach ($barangData as $id => $item) {
                // Get current barang price from database to ensure accuracy
                $barang = BarangModel::find($id);
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$id} tidak ditemukan!");
                }

                TransaksiDetailModel::create([
                    'penjualan_id' => $transaksi->penjualan_id,
                    'barang_id' => $id,
                    'harga' => $barang->harga_jual,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $barang->harga_jual * $item['jumlah']
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil diperbarui!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    // ADDITIONAL UTILITY FUNCTIONS

    // Function to generate transaction code
    private function generateTransactionCode()
    {
        $lastKode = TransaksiModel::select('penjualan_kode')
            ->orderBy('penjualan_kode', 'desc')
            ->value('penjualan_kode');

        $lastNumber = $lastKode ? (int)substr($lastKode, 3) : 0;
        return 'PJ-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    // Function to get new transaction code for form
    public function getNewCode()
    {
        $newCode = $this->generateTransactionCode();
        return response()->json([
            'status' => true,
            'code' => $newCode
        ]);
    }

    public function delete(string $id)
    {
        $transaksi = TransaksiModel::with(['user', 'detail_transaksi.barang'])
            ->where('penjualan_id', $id)
            ->firstOrFail();

        return view('transaksi.delete', compact('transaksi'));
    }

    public function destroy($id)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Cari transaksi
            $transaksi = TransaksiModel::find($id);

            if (!$transaksi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaksi tidak ditemukan!',
                ]);
            }

            // Hapus detail transaksinya
            TransaksiDetailModel::where('penjualan_id', $id)->delete();

            // Hapus header transaksinya
            $transaksi->delete();

            // Commit perubahan
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function export_excel()
    {
        $transaksi = TransaksiModel::with(['user', 'detail_transaksi.barang'])
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Petugas');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Kode Penjualan');
        $sheet->setCellValue('E1', 'Tanggal Penjualan');
        $sheet->setCellValue('F1', 'Total');

        $sheet->setCellValue('H1', 'No');
        $sheet->setCellValue('I1', 'Kode Penjualan');
        $sheet->setCellValue('J1', 'Nama Barang');
        $sheet->setCellValue('K1', 'Jumlah');
        $sheet->setCellValue('L1', 'Total');

        $sheet->getStyle('F1');
        $no = 1;
        $baris = 2;

        $noBarang = 1;
        $barisBarang = 2;

        foreach ($transaksi as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->user->nama);
            $sheet->setCellValue('C' . $baris, $value->pembeli);
            $sheet->setCellValue('D' . $baris, $value->penjualan_kode);
            $sheet->setCellValue('E' . $baris, $value->penjualan_tanggal);
            $sheet->setCellValue('F' . $baris, $value->total);

            $baris++;
            $no++;

            foreach ($value->detail_transaksi as $detail) {
                $sheet->setCellValue('H' . $barisBarang, $noBarang);
                $sheet->setCellValue('I' . $barisBarang, $value->penjualan_kode);
                $sheet->setCellValue('J' . $barisBarang, $detail->barang->barang_nama ?? '-');
                $sheet->setCellValue('K' . $barisBarang, $detail->jumlah);
                $sheet->setCellValue('L' . $barisBarang, $detail->harga);
        
                $barisBarang++;
                $noBarang++;
            }
        }
        foreach (range('A', 'L') as $columnID) {
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
}
