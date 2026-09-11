<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        $todayTransactions = Order::whereDate('created_at', today())->count();

        return view('transaksi.index', compact(
            'categories',
            'products',
            'todayTransactions'
        ));
    }

    public function store(Request $request)
    {
        $items = $request->items;

        if (! $items || count($items) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong.',
            ], 400);
        }

        DB::beginTransaction();

        try {

            $total = 0;

            // ==============================
            // 1. CEK PRODUK & HITUNG TOTAL
            // ==============================
            foreach ($items as $item) {

                $product = Product::find($item['id']);

                if (! $product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                $qty = (int) $item['qty'];

                if ($qty <= 0) {
                    throw new \Exception('Jumlah produk tidak valid.');
                }

                // CEK STOK
                if ($product->stok < $qty) {
                    throw new \Exception(
                        "Stok {$product->name} tidak cukup. Stok tersedia: {$product->stok}"
                    );
                }

                $total += $product->price * $qty;
            }

            // Pajak 11%
            $tax = $total * 0.11;

            $grandTotal = $total + $tax;

            // ==============================
            // 2. BUAT ORDER
            // ==============================

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-'.date('YmdHis'),
                'total_price' => (int) $grandTotal,
                'change' => (int) max(0, $request->order_change ?? 0),
                'payment_method' => $request->payment_method === 'midtrans' ? 1 : 0,
                'payment_status' => $request->payment_method === 'cash' ? 1 : 0,
            ]);

            // ==============================
            // 3. KURANGI STOK
            // ==============================

            foreach ($items as $item) {

                $product = Product::find($item['id']);

                $qty = (int) $item['qty'];

                // ⭐ INI YANG MENGURANGI STOK
                $product->decrement('stok', $qty);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'payment_method' => $request->payment_method,
                'message' => 'Transaksi berhasil dan stok berkurang.',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
