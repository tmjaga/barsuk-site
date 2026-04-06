<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Validation\ValidationException;
use Str;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }

    public function deleting(Category $category): void
    {
        $hasOrders = $category->services()->whereHas('orders')->exists();

        if ($hasOrders) {
            throw ValidationException::withMessages([
                'category' => "Can not delete {$category->title_localized} category. It contain services wich is used in one or more orders."]);
        }
    }

    public function saving(Category $category): void
    {
        $locale = config('logat.default');

        $title = $category->getTranslation('title', $locale);

        if (! $title) {
            return;
        }

        $category->slug = Str::slug($title).'-'.mt_rand(1000, 9999);
    }
}
