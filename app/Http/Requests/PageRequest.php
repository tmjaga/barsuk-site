<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string'],
            'sections' => ['required', 'array', 'min:1'],
            'sections.*.title' => ['required', 'regex:/^[A-Za-z0-9\-_]+$/'],
            'sections.*.content' => ['required', 'array'],
            'sections.*.content.*' => ['required', 'string'],
        ];

        // add rule for Add operation only
        if (! $this->route('page')) {
            $rules['slug'] = ['required', 'regex:/^[A-Za-z0-9_-]+$/'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => __('The Title field is required.'),
            'slug.required' => __('The Slug field is required.'),
            'sections.required' => __('You must add at least one section.'),
            'sections.array' => __('Sections must be an array.'),
            'sections.*.title.required' => __('The title for each section is required.'),
            'sections.*.title.regex' => __('Section title may only contain letters, numbers, hyphens, and underscores.'),
            'sections.*.content.required' => __('Each section must have translations.'),
            'sections.*.content.*.string' => __('Each translation must be a string.'),
            'sections.*.content.*.required' => __('Each section must have translations for all languages.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $sections = collect($this->sections)->map(function ($section) {
            $section['title'] = strtolower($section['title']);

            return $section;
        });

        $sections = $sections->toArray();

        $this->merge([
            'sections' => $sections,
        ]);
    }
}
