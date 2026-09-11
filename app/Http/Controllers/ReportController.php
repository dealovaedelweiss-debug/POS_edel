<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::query();

        // Filter berdasarkan rentang tanggal jika diisi
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        }

        $transactions = $query->latest()->get();

        // Menghitung total transaksi dan total pendapatan
        $totalTransaksi = $transactions->count();
        $totalPendapatan = $transactions->sum('total_price');

        return view('report.index', compact('transactions', 'totalTransaksi', 'totalPendapatan', 'startDate', 'endDate'));
    }
}
