<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::with('category')->get();
        $title = 'Data Product';

        return view('product.index', compact('title', 'product'));
    }

    public function create()
    {
        $title = 'Create New Product';
        $categories = Category::get();

        return view('product.create', compact('title', 'categories'));
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('products', 'public');
        }

        Product::create($data);

        return redirect()
            ->to('admin/product')
            ->with('success', 'Product berhasil ditambahkan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $title = 'Edit Product';
        $categories = Category::get();
        $edit = Product::findOrFail($id);

        return view(
            'product.edit',
            compact('title', 'categories', 'edit')
        );
    }

    public function update(Request $request, Product $product)
    {
        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        if ($request->hasFile('photo')) {

            if ($product->photo) {
                Storage::disk('public')
                    ->delete($product->photo);
            }

            $data['photo'] = $request->file('photo')
                ->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->to('admin/product')
            ->with('success', 'Update Product Success');
    }

    public function destroy(Product $product)
    {
        if ($product->photo) {
            Storage::disk('public')
                ->delete($product->photo);
        }

        $product->delete();

        return redirect()
            ->to('admin/product')
            ->with('success', 'Delete Berhasil');
    }
}