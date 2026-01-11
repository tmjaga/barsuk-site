<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $services = Service::query()
            ->with('category')
            ->when($category, fn ($q) => $q->where('category_id', $category))
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($services);
        }

        $categories = Category::orderBy('title')->get();

        return view('admin.catalog.service-index', compact('services', 'categories', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'hours' => 'required|digits:2',
                'minutes' => 'required|digits:2',
                'active' => 'sometimes|boolean',
            ]);

            // process 'active' checkbox
            $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

            // process duration
            $validated['duration'] = sprintf('%02d:%02d', $validated['hours'], $validated['minutes']);

            Service::create($validated);

            return response()->json([
                'message' => __('Service created successfully'),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Error updating Service', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Error updating Service')], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);

            return response()->json($service);
        } catch (Throwable $e) {
            Log::error('Service not found', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Service not found')], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'hours' => 'required|digits:2',
                'minutes' => 'required|digits:2',
                'active' => 'sometimes|boolean',
            ]);

            // process 'active' checkbox
            $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

            // process duration
            $validated['duration'] = sprintf('%02d:%02d', $validated['hours'], $validated['minutes']);

            $service->update($validated);

            return response()->json([
                'message' => __('Service updated successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Service update error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error updating Service'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
        try {
            $service->delete();

            return response()->json([
                'message' => __('Service deleted successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error deleting Service', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting service'),
            ], 500);
        }
    }
}
