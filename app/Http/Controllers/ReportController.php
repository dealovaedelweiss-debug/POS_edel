<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil tanggal dari form
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Query transaksi
        $query = Order::query();
        // Filter tanggal awal
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        // Filter tanggal akhir
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Ambil data transaksi
        $orders = $query
            ->orderBy('created_at', 'desc')
            ->get();

        // Total transaksi
        $totalTransactions = $orders->count();

        // Total pendapatan
        // Hanya transaksi yang sudah dibayar
        $totalRevenue = $orders
            ->where('payment_status', 1)
            ->sum('total_price');

        return view('report.index', compact(
            'orders',
            'totalTransactions',
            'totalRevenue',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
