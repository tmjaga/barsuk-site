<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected $casts = [
        'start_from' => 'time',
        'start_to' => 'time',
    ];

    public function index(): View
    {
        $settings = Setting::firstOrFail();

        return view('admin.pages.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'work_from' => 'required|date_format:H:i',
            'work_to' => 'required|date_format:H:i|after:work_from',
            'break_minutes' => 'required|integer|min:1',
            'slot_step_minutes' => 'required|integer|min:1',
        ]);

        $settings = Setting::firstOrFail();

        $settings->update($validated);

        Cache::forever('settings', $settings->fresh());

        return to_route('admin.settings.index')->with([
            'status' => __('Settings has been Updated'),
            'variant' => 'success',
        ]);
    }
}
