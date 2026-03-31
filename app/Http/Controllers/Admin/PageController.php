<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PageController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $pages = Page::with('sections')
            ->latest()
            ->paginate(config('app.items_per_page'));

        if ($request->ajax()) {
            return response()->json($pages);
        }

        return view('admin.pages.pages-index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.pages-addedit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $page = Page::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'template' => $request->template,
                'custom_template' => $request->custom_template ?? false,
            ]);

            // sections
            foreach ($request->sections as $sectionData) {
                $section = $page->sections()->create([
                    'key' => $sectionData['title'],
                ]);

                // section translations
                foreach ($sectionData['content'] as $locale => $value) {
                    $section->translations()->create([
                        'locale' => $locale,
                        'value' => $value,
                    ]);
                }
            }
        });

        Cache::forget("page_{$request->slug}");

        return to_route('admin.pages.index')->with([
            'status' => __('Page has been Created'),
            'variant' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page): View
    {
        $page->load(['sections.translations']);

        $existingSections = $page->sections->map(function ($section) {
            return [
                'id' => $section->id,
                'title' => $section->key,
                'content' => $section->translations->mapWithKeys(function ($t) {
                    return [$t->locale => $t->value];
                })->toArray(),
            ];
        })->toArray();

        return view('admin.pages.pages-addedit', compact('page', 'existingSections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        DB::transaction(function () use ($request, $page) {
            $page->update([
                'title' => $request->title,
                'template' => $request->template,
                'custom_template' => $request->custom_template ?? false,
            ]);

            foreach ($request->sections as $sectionData) {
                // insert or update sections
                $section = $page->sections()->updateOrCreate(
                    ['key' => $sectionData['title']],
                    ['type' => 'text']
                );

                // insert or update translations
                foreach ($sectionData['content'] as $locale => $value) {
                    $section->translations()->updateOrCreate(
                        ['locale' => $locale],
                        ['value' => $value]
                    );
                }
            }

            $existingKeys = collect($request->sections)->pluck('title')->toArray();
            $page->sections()->whereNotIn('key', $existingKeys)->delete();
        });

        Cache::forget("page_{$page->slug}");

        return to_route('admin.pages.index')->with([
            'status' => __('Page has been Updated'),
            'variant' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page): JsonResponse
    {
        try {
            Cache::forget("page_{$page->slug}");

            $page->delete();

            return response()->json([
                'message' => __('Page deleted successfully'),
            ]);
        } catch (Throwable $e) {
            Log::error('Error deleting Page', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => __('Error while deleting Page'),
            ], 500);
        }
    }
}
