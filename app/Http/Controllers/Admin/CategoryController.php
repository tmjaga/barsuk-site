<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $categories = Category::orderByDesc('id')->get();

        if ($request->ajax()) {
            return response()->json(['data' => $categories]);
        }

        $pages = Page::customTemplate()->get();

        return view('admin.catalog.category-index', compact('categories', 'pages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|array',
                'title.*' => 'required|string|max:255',
                'active' => 'sometimes|boolean',
                'page_id' => 'nullable|exists:pages,id',
            ]);

            // process 'active' checkbox
            $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

            Category::create($validated);

            return response()->json([
                'message' => __('Category created successfully'),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Error creating Category', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Error creating Category')], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json($category);
        } catch (Throwable $e) {
            Log::error('Category not found', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Category not found')], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|array',
                'title.*' => 'required|string|max:255',
                'active' => 'sometimes|boolean',
                'page_id' => 'nullable|exists:pages,id',
            ]);

            // process 'active' checkbox
            $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

            $category->update($validated);

            return response()->json([
                'message' => __('Category updated successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Category update error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error updating Category'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return response()->json([
                'message' => __('Category deleted successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error deleting category', [
                'message' => $e->getMessage(),
            ]);

            $errorMessage = __('Error while deleting service');

            if ($e instanceof ValidationException) {
                $errorMessage = $e->validator->errors()->first();
            }

            return response()->json([
                'message' => $errorMessage,
            ], 500);
        }
    }
}
