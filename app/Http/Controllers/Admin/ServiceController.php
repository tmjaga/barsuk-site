<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
            ->when($search, function ($q) use ($search) {
                $q->where('title->'.app()->getLocale(), 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($services);
        }

        $categories = Category::orderBy('title')->get();
        $pages = Page::customTemplate()->get();

        return view('admin.catalog.service-index', compact('services', 'pages', 'categories', 'category'));
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
                'description' => 'nullable|array',
                'description.*' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'page_id' => 'nullable|exists:pages,id',
                'hours' => 'required|digits:2',
                'minutes' => 'required|digits:2',
                'active' => 'sometimes|boolean',
                'price' => 'required|numeric|regex:/^\d{1,8}(\.\d{1,2})?$/',
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

            return response()->json([
                'id' => $service->id,
                'category_id' => $service->category_id,
                'page_id' => $service->page_id,
                'title' => $service->getTranslations('title'),
                'description' => $service->getTranslations('description'),
                'duration' => $service->duration,
                'price' => $service->price,
                'active' => $service->active,
            ]);
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
                'title' => 'required|array',
                'title.*' => 'required|string|max:255',
                'description' => 'nullable|array',
                'description.*' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'page_id' => 'nullable|exists:pages,id',
                'hours' => 'required|digits:2',
                'minutes' => 'required|digits:2',
                'active' => 'sometimes|boolean',
                'price' => 'required|numeric|regex:/^\d{1,8}(\.\d{1,2})?$/',
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
