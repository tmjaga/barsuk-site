<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniquePageSectionKey implements ValidationRule
{
    protected $pageId;

    protected $sections;

    public function __construct($pageId, $sections)
    {
        $this->pageId = $pageId;
        $this->sections = $sections;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1];
        $sectionId = $this->sections[$index]['id'] ?? null;

        $exists = DB::table('page_sections')
            ->where('page_id', $this->pageId)
            ->where('key', $value)
            ->when($sectionId, fn ($q) => $q->where('id', '!=', $sectionId))
            ->exists();

        if ($exists) {
            $fail(__('Section ":key" already exists on this page.', [
                'key' => $value,
            ]));
        }
    }
}
