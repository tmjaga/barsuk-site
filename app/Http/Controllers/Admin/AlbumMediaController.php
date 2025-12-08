<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Album;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            ->orderByDesc('id')
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

    public function store(Request $request, Album $album): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
            'file' => 'required|image|mimes:jpg,jpeg|max:'.(int) (config('media-library.max_file_size') / 1024),
        ]);

        // process 'active' checkbox
        $validated['active'] = Status::from((int) ($validated['active'] ?? 0));

        try {
            $status = __('Image has been Added');
            $variant = 'success';

            $file = $validated['file'];

            $album->addMedia($file)
                ->usingFileName(Str::random(10).'_'.$file->getClientOriginalName())
                ->withCustomProperties([
                    'title' => $validated['title'],
                    'active' => $validated['active'],
                ])
                ->toMediaCollection('images');
        } catch (\Throwable $e) {
            Log::error('Image upload error', [
                'message' => $e->getMessage(),
            ]);
            $status = __('Error uploading image');
            $variant = 'error';
        }

        return to_route('admin.albums.media.index', $album->id)->with([
            'status' => $status,
            'variant' => $variant,
        ]);
    }

    public function update(Request $request, Album $album, Media $image): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'sometimes|boolean',
            'file' => 'nullable|image|mimes:jpg,jpeg|max:'.(int) (config('media-library.max_file_size') / 1024),
        ]);

        // process 'active' checkbox
        $validated['active'] = Status::from((int) ($validated['active'] ?? 0));
        try {
            $status = __('Image has been updated');
            $variant = 'success';

            if ($request->hasFile('file')) {
                $file = $validated['file'];
                $oldId = $image->id;
                
                $image->delete();

                $newMedia = $album->addMedia($file)
                    ->usingFileName(Str::random(10).'_'.$file->getClientOriginalName())
                    ->withCustomProperties([
                        'title' => $validated['title'],
                        'active' => $validated['active'],
                    ])
                    ->toMediaCollection('images');

                DB::table('media')->where('id', $newMedia->id)->update(['id' => $oldId]);
            } else {
                $image->setCustomProperty('title', $validated['title']);
                $image->setCustomProperty('active', $validated['active']);
                $image->save();
            }
        } catch (\Throwable $e) {
            Log::error('Image update error', [
                'message' => $e->getMessage(),
            ]);
            $status = __('Error updating image');
            $variant = 'error';
        }

        return to_route('admin.albums.media.index', $album->id)->with([
            'status' => $status,
            'variant' => $variant,
        ]);
    }

    public function destroy(Album $album, Media $image): RedirectResponse
    {
        if ($image->model_id !== $album->id || $image->model_type !== Album::class) {
            abort(404);
        }

        $image->delete();

        return redirect()->back()->with([
            'status' => __('Image has been deleted'),
            'variant' => 'success',
        ]);
    }
}
