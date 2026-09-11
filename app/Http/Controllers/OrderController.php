<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        return $request;
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalPrice = 0;

            // 1. Validasi ketersediaan stok
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                // Cek otomatis pakai 'stok' (Indo) atau 'stock' (Inggris)
                $stokTersedia = $product->stock ?? 0;

                // if ($stokTersedia < $item['qty']) {
                //     return response()->json([
                //         'message' => "Stok {$product->name} tidak cukup. Stok tersedia: ".$stokTersedia,
                //         'stock' => $product->stock,
                //     ], 400); // 400 Bad Request
                // }

                $totalPrice += $product->price * $item['qty'];
            }

            // 2. Simpan data utama Order (Cash)
            $order = Order::create([
                'customer_name' => $request->customer_name ?? 'Umum',
                'total_price' => $totalPrice,
                'payment_method' => 'cash',
                'payment_status' => 1,
                'order_change' => $request->order_change ?? 0,
            ]);

            // 3. Simpan detail pesanan dan kurangi stok produk
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->price,
                ]);

                // Kurangi stok sesuai nama kolom di database Anda
                if (isset($product->stok)) {
                    $product->stok -= $item['qty'];
                } else {
                    $product->stock -= $item['qty'];
                }
                $product->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ], 500);
        }
    }
}
