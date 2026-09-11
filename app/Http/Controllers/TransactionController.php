<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Routing\Controller;

class TransactionController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        $todayTransactions = Order::whereDate('created_at', today())->count();

        return view('transaksi.index', compact('categories', 'products', 'todayTransactions'));
    }
}
