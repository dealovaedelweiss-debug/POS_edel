<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $todayTransactions = Order::whereDate('created_at', today())->count();
        $todaySales = Order::whereDate('created_at', today())
            ->sum('total_price');
        $productSold = OrderDetail::whereHas('order', function ($query) {
            $query->whereDate('created_at', today())->where('payment_status', 1);})->sum('qty');

        return view('dashboard.index', compact('todayTransactions', 'todaySales', 'productSold'));
    }
}
