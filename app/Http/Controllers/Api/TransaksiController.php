<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiModel;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\DetailTransaksiModel;
use App\Models\TransaksiDetailModel;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        // Mengambil semua data transaksi
        $transaksi = TransaksiModel::with(['user', 'detail_transaksi.barang'])->get();

        return response()->json([
            'transaksi' => $transaksi,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required|integer',   // user_id harus berupa integer
            'pembeli'           => 'required|string',    // pembeli harus berupa string
            'penjualan_kode'    => 'required|string',    // penjualan_kode harus berupa string
            'penjualan_tanggal' => 'required|date',      // penjualan_tanggal harus berupa tanggal yang valid
        ]);

        // Mengecek apakah validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        // Menyimpan data penjualan ke dalam database
        $transaksi = TransaksiModel::create([
            'user_id'           => $request->user_id,            // Menyimpan user_id
            'pembeli'           => $request->pembeli,            // Menyimpan nama pembeli
            'penjualan_kode'    => $request->penjualan_kode,     // Menyimpan kode penjualan
            'penjualan_tanggal' => $request->penjualan_tanggal,  // Menyimpan tanggal penjualan
        ]);

        return response()->json([
            'status' => true,
            'transaksi' => $transaksi,
        ], 201);

        return response()->json([
            'status' => false,
            'message' => 'Transaksi registration failed',
        ], 422);
    }

    public function update(Request $request, TransaksiModel $transaksi)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required|integer',
            'pembeli'           => 'required|string',
            'penjualan_kode'    => 'required|string',
            'penjualan_tanggal' => 'required|date',
        ]);

        // Kalau validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // Update data
        $transaksi->update([
            'user_id'           => $request->user_id,
            'pembeli'           => $request->pembeli,
            'penjualan_kode'    => $request->penjualan_kode,
            'penjualan_tanggal' => $request->penjualan_tanggal,
        ]);

        return response()->json([
            'status' => true,
            'transaksi' => $transaksi,
        ], 200);
    }


    public function show(TransaksiModel $transaksi)
    {
        $transaksi->load(['detail_transaksi.barang']);

        return response()->json([
            'transaksi' => $transaksi,
        ]);
    }

    public function storeDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penjualan_id' => 'required|integer',   // user_id harus berupa integer
            'barang_id' => 'required|int',    // pembeli harus berupa string
            'harga' => 'required|int',    // penjualan_kode harus berupa string
            'jumlah' => 'required|int',      // penjualan_tanggal harus berupa tanggal yang valid
        ]);

        // Mengecek apakah validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }
        // Menyimpan data penjualan ke dalam database
        $transaksi = TransaksiModel::find($request->penjualan_id);
        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi not found',
            ], 404);
        }

        $transaksi->detail_transaksi()->create([
            'penjualan_id' => $request->penjualan_id,            // Menyimpan user_id
            'barang_id' => $request->barang_id,            // Menyimpan nama pembeli
            'harga' => $request->harga,     // Menyimpan kode penjualan
            'jumlah' => $request->jumlah,  // Menyimpan tanggal penjualan
        ]);

        $transaksi->save();

        return response()->json([
            'status' => true,
            'transaksi' => $transaksi,
        ], 201);

        return response()->json([
            'status' => false,
            'message' => 'Transaksi registration failed',
        ], 422);
    }

    public function updateDetail(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'penjualan_id' => 'required|integer',   // penjualan_id harus berupa integer
            'barang_id' => 'required|int',    // barang_id harus berupa integer
            'harga' => 'required|int',    // harga harus berupa integer
            'jumlah' => 'required|int',      // jumlah harus berupa integer
        ]);

        // Mengecek apakah validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        // Mencari transaksi berdasarkan penjualan_id
        $transaksi = TransaksiModel::find($request->penjualan_id);
        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi not found',
            ], 404);
        }

        // Mencari detail transaksi berdasarkan id
        $detailTransaksi = $transaksi->detail_transaksi()->find($id);
        if (!$detailTransaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Detail Transaksi not found',
            ], 404);
        }

        // Melakukan update pada detail transaksi
        $detailTransaksi->update([
            'penjualan_id' => $request->penjualan_id,  // Menyimpan penjualan_id
            'barang_id' => $request->barang_id,        // Menyimpan barang_id
            'harga' => $request->harga,                // Menyimpan harga
            'jumlah' => $request->jumlah,              // Menyimpan jumlah
        ]);

        return response()->json([
            'status' => true,
            'transaksi' => $transaksi,
            'message' => 'Detail Transaksi updated successfully',
        ], 200);
    }

    public function deleteDetail($id)
    {
        // Mencari transaksi detail berdasarkan ID
        $detailTransaksi = TransaksiDetailModel::find($id);

        // Mengecek apakah detail transaksi ditemukan
        if (!$detailTransaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Detail transaksi not found',
            ], 404);
        }

        // Menghapus detail transaksi
        $detailTransaksi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Detail transaksi berhasil dihapus',
        ], 200);
    }


    public function destroy(TransaksiModel $transaksi)
    {
        // Menghapus transaksi beserta detailnya
        $transaksi->delete();  // Dengan cascading delete, detail-transaksi juga akan terhapus otomatis

        return response()->json([
            'status' => true,
            'message' => 'Transaksi and its details deleted successfully'
        ]);
    }
}
