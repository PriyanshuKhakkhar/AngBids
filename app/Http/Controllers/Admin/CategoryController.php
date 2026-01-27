<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', function($row){
                    $icon = $row->icon ?? 'fas fa-tag';
                    return '<i class="'.$icon.' text-primary"></i>';
                })
                ->addColumn('status', function($row){
                    if($row->is_active){
                         return '<span class="badge badge-success">Active</span>';
                    }
                    return '<span class="badge badge-secondary">Inactive</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.categories.edit', $row->id).'" class="btn btn-sm btn-circle btn-info mr-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    
                    $btn .= '<button type="button" class="btn btn-sm btn-circle btn-danger delete-category" data-id="'.$row->id.'" data-url="'.route('admin.categories.destroy', $row->id).'" title="Delete"><i class="fas fa-trash"></i></button>';
                    
                    return $btn;
                })
                ->rawColumns(['icon', 'status', 'action'])
                ->make(true);
        }
        
        return view('admin.categories.index', [
            'total_categories' => Category::count()
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|min:2|max:255|unique:categories,name',
            'icon'      => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        Category::create([
            'name'      => trim($validated['name']),
            'slug'      => Str::slug($validated['name']),
            'icon'      => $validated['icon'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|min:2|max:255|unique:categories,name,' . $category->id,
            'icon'      => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name'      => trim($validated['name']),
            'slug'      => Str::slug($validated['name']),
            'icon'      => $validated['icon'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Category deleted successfully']);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
