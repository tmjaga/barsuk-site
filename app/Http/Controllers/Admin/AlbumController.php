<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $search = $request->query('search');

        $albums = Album::query()
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($albums);
        }

        return view('admin.gallery.albums-index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.gallery.albums-addedit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        // process 'active' checkbox
        $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

        Album::create($validated);

        return to_route('admin.albums.index')->with([
            'status' => __('Album has been Created'),
            'variant' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album): View
    {
        return view('admin.gallery.albums-addedit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album): RedirectResponse
    {
        // TODO on Edit operation try to store current page and search parameters to return to page with search value
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        // process 'active' checkbox
        $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

        $album->update($validated);

        return to_route('admin.albums.index')->with([
            'status' => __('Album has been Updated'),
            'variant' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album): JsonResponse
    {
        try {
            $album->delete();

            return response()->json([
                'message' => __('Album deleted successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error deleting album', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting album'),
            ], 500);
        }
    }
}
