<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Services\CategorySpecificationService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $specService;

    public function __construct(CategorySpecificationService $specService)
    {
        $this->specService = $specService;
    }

    /**
     * Get all active categories in a hierarchy.
     */
    public function index()
    {
        $categories = Category::topLevel()
            ->active()
            ->with(['children' => function($q) {
                $q->active();
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories)
        ]);
    }

    /**
     * Get dynamic specifications for a specific category.
     */
    public function specifications($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        
        $specs = $this->specService->getSpecifications($category->slug);
        
        // Also check parent if no specs found for child
        if (empty($specs) && $category->parent_id) {
            $parent = $category->parent;
            if ($parent) {
                $specs = $this->specService->getSpecifications($parent->slug);
            }
        }

        $config = config("auction_specs.{$category->slug}") ?? config("auction_specs.{$category->parent?->slug}");

        return response()->json([
            'success' => true,
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'fields' => $specs,
                'has_document' => $config['has_document'] ?? false,
                'document_label' => $config['document_label'] ?? 'Document',
                'document_hint' => $config['document_hint'] ?? null,
            ]
        ]);
    }
}
