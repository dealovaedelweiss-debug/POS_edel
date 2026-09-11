<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Data Category';
        $categories = Category::orderBy('name', 'asc')->get();

        // UNTUK MENGAMBIL DATA PADA MODEL Category
        return view('category.index', compact('title', 'categories'));
    }

    public function create()
    {
        $title = 'Create New Category';

        return view('category.create', compact('title'));
    }

    public function store(Request $request)
    {

        Category::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->to('admin/category');
    }

    public function edit(Request $request, int $id)
    {
        $title = 'Edit Category';
        $edit = Category::findOrFail($id);

        return view('category.edit', compact('edit', 'title'));
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->to('admin/category');
    }

    public function destroy(int $id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->to('admin/category');
    }
}
