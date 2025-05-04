<?php

namespace App\Http\Controllers;

use App\Models\TransaksiModel;
use App\Models\TransaksiDetailModel;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function chartData(Request $request)
    {
        $now = Carbon::now();

        $filter = $request->get('filter', 'monthly');

        switch ($filter) {
            case 'daily':
                $transaksi = TransaksiModel::with('detail_transaksi')->get();

                $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $data = [12, 19, 3, 5, 2, 3, 7];
                break;
            case 'weekly':
                $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                $data = [50, 60, 70, 65];
                break;
            case 'monthly':
                $transaksi = TransaksiModel::orderBy('penjualan_tanggal')->get();

                $start = Carbon::parse($transaksi->first()->penjualan_tanggal);
                $end = Carbon::parse($transaksi->last()->penjualan_tanggal);

                // Hitung interval
                $interval = $start->diffInDays($end) / 4;

                $labels = [];
                $data = [];

                for ($i = 0; $i < 5; $i++) {
                    $labelStart = $start->copy()->addDays($i * $interval);
                    $labelEnd = $start->copy()->addDays(($i + 1) * $interval);

                    $labels[] = $labelStart->format('d M');

                    $transaksiInRange = TransaksiModel::with('detail_transaksi.barang')
                        ->whereBetween('penjualan_tanggal', [$labelStart, $labelEnd])
                        ->get();

                    $total = $transaksiInRange->sum(function ($transaksi) {
                        return $transaksi->total; // ini manggil getTotalAttribute()
                    });

                    $data[] = $total;
                }
                break;
            case 'yearly':
                $labels = ['2021', '2022', '2023', '2024'];
                $data = [100, 150, 200, 175];
                break;
            default:
                $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $data = [12, 19, 3, 5, 2, 3, 7];
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
