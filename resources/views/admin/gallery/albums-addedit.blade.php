@extends('admin.layouts.app')

@section('content')
    @php
        $pageTitle = @isset($album) ? __('Edit Album: '). $album->title : __('Create New Album');
        $pageLabel = @isset($album) ? __('Edit Album') : __('Create New Album');
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ $pageTitle }}" :breadcrumbs="[
        ['label' => 'Albums', 'url' => route('admin.albums.index')],
        ['label' => $pageLabel, 'url' => '#']
    ]" >
    </x-common.page-breadcrumb>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div x-data="album_validate()" class="space-y-6 border-t border-gray-100 p-5 sm:p-6">
            <form @submit.prevent="submit" action="{{ isset($album) ? route('admin.albums.update', $album->id) : route('admin.albums.store') }}" method="POST">
                @csrf
                @isset($album)
                    @method('PUT')
                @endisset

                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            {{__('Album Title')}} <span class="text-red-500">*</span>
                        </label>
                        <input name="title" x-model="title" value="" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                        <p x-show="$v.title.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">
                            {{__('Please enter a valid Title')}}
                        </p>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="px-2.5">
                        <x-forms.checkbox-status
                            name="active"
                            label="{{__('Active')}}"
                            :checked="($album->active->value ?? 1) == 1"
                        />
                    </div>
                    <div class="w-full px-2.5">
                        <div class="mt-1 flex items-center gap-3">
                            <button type="submit" class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white">
                                {{__('Save Changes')}}
                            </button>

                            <a href="{{ route('admin.albums.index') }}" class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">
                                {{__('Cancel')}}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('album_validate', () => ({
                title: @json(old('title', $album->title ?? '')),

                init() {
                    this.$validation(this);
                },
                validations: {
                    title: ['required', 'min:3'],
                },
                submit() {
                    this.title = this.title.trim();
                    this.$v.validate();

                    if (this.$v.title.$invalid) {
                        return;
                    }

                    this.$el.submit();
                }
            }));
        });
    </script>

@endpush


