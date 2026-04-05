<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Validation\ValidationException;
use Str;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        //
    }

    public function saving(Service $service): void
    {
        $locale = config('logat.default');

        $title = $service->getTranslation('title', $locale);

        if (! $title) {
            return;
        }

        $service->slug = Str::slug($title).'-'.mt_rand(1000, 9999);
    }

    public function deleting(Service $service): void
    {
        if ($service->orders()->exists()) {
            throw ValidationException::withMessages(['service' => "Can not delete {$service->title_localized} service. It used in one or more orders."]);
        }
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        //
    }
}
