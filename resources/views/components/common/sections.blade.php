@props(['existingSections' => [], 'errors' => []])

@php
    $languages = config('logat.languages');
    $initialSections = old('sections', $existingSections ?? [['title' => '', 'content' => [], 'activeTab' => 'en']]);
@endphp
<div x-data="sectionsBuilder(@js($initialSections), @js($errors->toArray()), @js(array_keys($languages)))" class="space-y-6">

    <template x-for="(section, index) in sections" :key="section.uid">
        <div class="rounded-xl border border-gray-200 p-6">

            {{-- Section header --}}
            <div class="flex justify-between items-center mb-5">
                <div class="w-full max-w-sm">
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">
                        {{ __('Section Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="hidden" :name="`sections[${index}][id]`" :value="section.id">
                    <input type="text" :name="`sections[${index}][title]`" x-model="section.title" @input="section.title = section.title.replace(/[^A-Za-z0-9_-]/g,'')"
                           class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                    <template x-if="validationErrors?.[`sections.${index}.title`] && validationErrors[`sections.${index}.title`].length">
                        <div class="text-red-500 text-sm mt-1"
                             x-text="validationErrors[`sections.${index}.title`][0]">
                        </div>
                    </template>
                </div>

                {{-- Delete button --}}
                <button type="button" @click="removeSection(index)" x-show="sections.length > 1"
                    class="ml-3 w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600 transition">
                    <x-heroicon-o-trash class="w-5 h-5" />
                </button>
            </div>

            {{-- Tabs --}}
            <div x-data="{ activeTab: section.activeTab }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-2 overflow-x-auto">
                        @foreach($languages as $code => $lang)
                            <button type="button" @click="activeTab='{{ $code }}'"
                                :class="activeTab === '{{ $code }}' ? 'text-blue-500 border-blue-500' : 'text-gray-500 border-transparent'"
                                class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium">
                                <span class="fi fi-{{ $lang['flag'] }}"></span>
                                {{ $lang['label'] }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                {{-- Editors --}}
                <div class="pt-4">
                    @foreach($languages as $code => $lang)
                        <div x-show="activeTab === '{{ $code }}'">
                            <h3 class="mb-2 text-lg font-medium">{{ $lang['label'] }}</h3>
                            <input type="hidden" :id="'content_'+section.uid+'_{{ $code }}'" :name="`sections[${index}][content][{{ $code }}]`" x-model="section.content['{{ $code }}']">
                            <trix-editor :input="'content_'+section.uid+'_{{ $code }}'" class="bg-white border border-gray-300 rounded-lg" style="min-height:150px"></trix-editor>

                            <template x-if="getTabError(index, '{{ $code }}')">
                                <div class="text-red-500 text-sm mt-1" x-text="getTabError(index, '{{ $code }}')"></div>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </template>
    <div class="text-end">
        <button type="button" @click="addSection()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            {{ __('New Section') }}
        </button>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sectionsBuilder', (existing = [],  errors = {}, languages = []) => ({
            sections: existing.length ? existing.map((s, index) => ({
                uid: Date.now() + Math.random(),
                id: s.id ?? null,
                title: s.title || '',
                content: s.content || {},
                activeTab: languages.find(lang => errors[`sections.${index}.content.${lang}`]) || languages[0]
                })) : [{ uid: Date.now(), id:null, title: '', content: {}, activeTab: languages[0] }],
            validationErrors: errors,
            languages: languages,

            getTabError(index, lang) {
                return this.validationErrors[`sections.${index}.content.${lang}`]?.[0] ?? null;
            },

            addSection() {
                this.sections.push({ uid: Date.now() + Math.random(), title: '', content: {}, activeTab: this.languages[0] });

                this.$nextTick(() => {
                    document.querySelectorAll("trix-editor").forEach(el => {
                        if (!el.editor) el.dispatchEvent(new Event("trix-initialize"));
                    });
                });
            },

            removeSection(index) {
                if(this.sections.length > 1) {
                    this.sections.splice(index, 1);
                }
            }
        }));
    });
</script>
