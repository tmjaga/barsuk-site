<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $search = $request->query('search');

        $reviews = Review::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($reviews);
        }

        return view('admin.reviews.review-index', compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            Review::create($request->validated());

            return response()->json([
                'message' => __('Review created successfully'),
            ], 201);
        } catch (Throwable $e) {
            Log::error('Error updating Review', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Error updating Review')], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);

            return response()->json($review);
        } catch (Throwable $e) {
            Log::error('Review not found', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => __('Review not found')], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreReviewRequest $request, Review $review): JsonResponse
    {
        try {
            $review->update($request->validated());

            return response()->json([
                'message' => __('Review updated successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Review update error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error updating Order'),
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): JsonResponse
    {
        try {
            $review->delete();

            return response()->json([
                'message' => __('Review deleted successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error deleting Review', ['message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __(__('Error while deleting Review')),
            ], 500);
        }
    }
}
