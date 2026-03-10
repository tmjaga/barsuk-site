<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class SubscriberController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $search = $request->query('search');

        $subscribers = Subscriber::query()
            ->when($search, fn ($q) => $q->where('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($subscribers);
        }

        return view('admin.subscribers.subscriber-index', compact('subscribers'));
    }

    public function deleteSelected(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:subscribers,id',
            ]);

            Subscriber::whereIn('id', $request->ids)->delete();

            return response()->json([
                'message' => __('Subscribers deleted successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error deleting Subscribers', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting Subscribers'),
            ], 500);
        }
    }
    public function changeStatus(Subscriber $subscriber, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'is_verified' => 'required|integer|in:0,1',
            ]);

            $subscriber->update($validated);

            return response()->json([
                'message' => __('Subscriber updated successfully'),
            ]);

        } catch (Throwable $e) {
            Log::error('Error updating Subscriber', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while update Subscriber'),
            ], 500);
        }
    }
}
