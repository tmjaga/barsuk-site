<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumMediaController extends Controller
{
    public function index(Request $request, Album $album): JsonResponse|View
    {
        $search = $request->query('search');

        $images = Media::where('model_type', Album::class)
            ->where('model_id', $album->id)
            ->where('collection_name', 'images')
            // ->orderBy('order_column')
            ->orderBy('id')
            ->when($search, function ($query) use ($search) {
                $query->where('custom_properties->title', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(config('app.items_per_page'))
            ->withQueryString();

        if ($request->ajax()) {
            $images->getCollection()->transform(function ($image) {
                $image->url = $image->getUrl();

                return $image;
            });

            return response()->json($images);
        }

        return view('admin.gallery.media-index', compact('album', 'images'));
    }

    public function create(Album $album): View
    {
        return view('admin.gallery.media-addedit', compact('album'));
    }

    public function edit(Album $album, Media $image): View
    {
        return view('admin.gallery.media-addedit', compact('album', 'image'));
    }


    public function destroy(Album $album, Media $media): RedirectResponse
    {

        dd($album, $media);
    }
}
