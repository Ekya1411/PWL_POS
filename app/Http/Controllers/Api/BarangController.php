<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id'   => 'required|string',    // misalnya kategori_id adalah string
            'barang_kode'   => 'required|string',    // misalnya kode barang adalah string
            'barang_nama'   => 'required|string',    // nama barang adalah string
            'harga_beli'    => 'required|integer',   // harga beli harus integer
            'harga_jual'    => 'required|integer',   // harga jual juga integer
            'image'         => 'required|image'     // validasi untuk gambar
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $barang = BarangModel::create([
            'kategori_id'   => $request->kategori_id,       // id kategori yang dipilih
            'barang_kode'   => $request->barang_kode,       // kode barang
            'barang_nama'   => $request->barang_nama,       // nama barang
            'harga_beli'    => $request->harga_beli,        // harga beli
            'harga_jual'    => $request->harga_jual,        // harga jual
            'image'         => $request->image->hashName(), // menyimpan nama file gambar yang di-upload
        ]);

        return response()->json([
            'status' => true,
            'barang' => $barang,
        ], 201);

        return response()->json([
            'status' => false,
            'message' => 'Create barang failed',
        ], 422);
    }

    public function show(BarangModel $barang)
    {
        return response()->json($barang);
    }

    public function update(Request $request, BarangModel $barang)
    {
        $barang->update($request->all());
        return response()->json($barang);
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'status' => true,
            'message' => 'Barang deleted successfully'
        ]);
    }
}
