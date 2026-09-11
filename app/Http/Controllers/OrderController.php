<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('order.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        $products = Product::OrderBy('id')->get();

        return view('order.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
        ]);
        try {
            $snapToken = null;
            $orderId = null;
            DB::transaction(function () use ($request, &$orderId, &$snapToken) {
                $subtotal = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $product = Product::find($item['id']);
                    if ($product->stock < $item['stock']) {
                        return response()->json([
                            'message' => 'Tidak ada stock',
                        ], 422);
                    }
                    $itemSubTotal = $product->price * $item['stock'];
                    $subtotal += $itemSubTotal;

                    $itemsData[] = [
                        'product' => $product,
                        'stock' => $item['stock'],
                        'price' => $product->price,
                        'subtotal' => $itemSubTotal,
                    ];
                }

                $tax = $subtotal * 0.1;
                $total = $subtotal + $tax;
                $orderCode = 'ORD-'.date('Ymd').'-'.rand(1000, 9999);
                $paymentMethod = $request->payment_method ?? 'cash';

                $order = Order::create([
                    'order_code' => $orderCode,
                    'order_amount' => $total,
                    'order_change' => $request->order_change,
                    'order_status' => $paymentMethod === 'cash' ? 'success' : 'pending',
                ]);

                $orderId = $order->id;
                // order detail
                foreach ($itemsData as $data) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product']->id,
                        'order_stock' => $data['stock'],
                        'order_price' => $data['price'],
                        'order_subtotal' => $data['subtotal'],
                    ]);
                    if ($paymentMethod === 'cash') {
                        $data['product']->decrement('stock', $data['stock']);
                    }
                }
                if ($paymentMethod === 'midtrans') {
                    Config::$serverKey = config('services.midtrans.server_key');
                    Config::$clientKey = config('services.midtrans.client_key');
                    Config::$isProduction = config('services.midtrans.is_production', false);
                    Config::$isSanitized = true;
                    Config::$is3ds = true;

                    $params = [
                        'transaction_details' => [
                            'order_id' => $order->order_code,
                            'gross_amount' => (int) round($total),
                        ],
                        'customer_details' => [
                            'first_name' => $request->customer_name ?? 'No-Name',
                        ],
                        // 'enabled_payments' => ['gopay', 'qris'],
                    ];

                    $snapToken = Snap::getSnapToken($params);
                }
            });
            if ($request->payment_method === 'midtrans') {
                return response()->json([
                    'success' => true,
                    'payment_method' => 'midtrans',
                    'snap_token' => $snapToken,
                    'order_id' => $orderId,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'payment_method' => 'cash',
                    'order_id' => $orderId,
                ]);
            }
        } catch (Exception $th) {
            // kalau gagal
            return response()->json([
                'message' => 'Gagal Menyimpan transaksi'.$th->getMessage(),
            ], 500);
        }
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
