<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Subscriber;
use App\Notifications\NewOrderNotification;
use App\Notifications\NewSubscriberNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SubscribeController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // send notification to admin
        $admin = Admin::find(1);
        Notification::send($admin, new NewSubscriberNotification($subscriber));

        return response()->json([
            'message' => __('You have successfully subscribed!'),
        ], 201);
    }
}
