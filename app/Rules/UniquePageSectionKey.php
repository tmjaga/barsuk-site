<?php

namespace App\Rules;

use App\Models\Page;
use App\Models\PageSection;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePageSectionKey implements ValidationRule
{
    public function __construct(protected ?Page $page, protected array $sections) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1];
        $sectionId = $this->sections[$index]['id'] ?? null;

        $query = $this->page ? $this->page->sections() : PageSection::query()->where('page_id', request('page_id'));

        $exists = $query->where('key', $value)
            ->when($sectionId, fn ($q) => $q->where('id', '!=', $sectionId))
            ->exists();

        if ($exists) {
            $fail(__('Section ":key" already exists on this page.', [
                'key' => $value,
            ]));
        }
    }
}
