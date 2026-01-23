<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        \App\Models\Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
