<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Contracts\View\View;

class FrontendController extends Controller
{
    public function aboutUs(): View
    {
        $reviews = Review::active()
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('pages.about', compact('reviews'));
    }
}
