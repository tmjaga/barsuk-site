<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrixController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:2048'], // 2MB
        ]);

        $path = $request->file('file')->store('images/uploads', 'public');

        return response()->json([
            'url' => asset(Storage::url($path)),
        ]);
    }
}
