<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $search = $request->query('search');
        $albums = Album::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        // dd($albums);
        // $albums = Album::latest()->paginate(config('app.items_per_page'));

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
        //dd('new', $request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        $validated['active'] = Status::from((int) ($validated['active'] ?? 0));


        Album::create($validated);

        return to_route('admin.albums.index')->with([
            'status' => __('Album has been Created'),
            'variant' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

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
    public function destroy(Album $album): RedirectResponse
    {
        $album->delete();

        return redirect()->back()->with([
            'status' => __('Album has been deleted'),
            'variant' => 'success',
        ]);
    }
}
