<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Admin;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function aboutUs(): View
    {
        $reviews = Review::active()
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('pages.templates.about', compact('reviews'));
    }

    public function contactUs(): View
    {
        return view('pages.templates.contact');
    }

    public function contactMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:5',
            'email' => 'required|email',
            'subject' => 'required|string|min:5',
            'message' => 'required|string',
        ]);

        // send notification to admin
        $admin = Admin::find(1);
        Mail::to($admin->email)->send(new ContactMail($validated));
        // $admin->notify(new NewSubscriberNotification($subscriber));

        return response()->json([
            'message' => __('Your message was sent successfully'),
        ]);
    }
}
