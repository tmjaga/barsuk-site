<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $notifications = Auth::guard('admin')
            ->user()
            ->notifications()
            ->paginate(config('app.items_per_page'));

        if ($request->ajax()) {
            return response()->json($notifications);
        }

        return view('admin.notifications.notifications-index', compact('notifications'));
    }

    public function destroy($id): JsonResponse
    {
        try {
            $notification = Auth::guard('admin')->user()->notifications()->findOrFail($id);
            $notification->delete();

            return response()->json([
                'message' => __('Notification deleted successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error deleting notification', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting notification'),
            ], 500);
        }
    }

    public function markAsRead($id): JsonResponse
    {
        try {
            $notification = Auth::guard('admin')->user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            return response()->json([
                'message' => __('Notification marked as read.'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error updating notification', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while updating notification'),
            ], 500);
        }
    }

    public function fetch(): JsonResponse
    {
        $user = Auth::guard('admin')->user();

        return response()->json([
            'notifications' => $user->notifications()->latest()->take(10)->get()->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->data['type'],
                'message' => $n->data['message'],
                'read' => ! is_null($n->read_at),
                'time' => Carbon::parse($n->created_at)->diffForHumans(),
            ]),
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAllRead(): JsonResponse
    {
        try {
            $user = Auth::guard('admin')->user();
            $user->unreadNotifications->markAsRead();

            return response()->json([
                'message' => __('All notifications marked as read.'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error updating all notifications status', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while updating notification'),
            ], 500);
        }
    }
}
