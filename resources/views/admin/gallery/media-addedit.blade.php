@extends('admin.layouts.app')

@section('content')
    @php
        $pageTitle = @isset($image) ? __('Edit Image: ').' ['. $album->title.']' : __('Add New Image');
        $pageLabel = @isset($image) ? __('Edit Image') : __('Add New Image');
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ $pageTitle }}" :breadcrumbs="[
        ['label' => __('Albums'), 'url' => route('admin.albums.index')],
        ['label' => __('Album').' ['.$album->title.']', 'url' => route('admin.albums.media.index', $album->id)],
        ['label' => $pageLabel, 'url' => '#']
    ]" >
    </x-common.page-breadcrumb>

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div x-data="image_validate('{{ $image?->getUrl() ?? '' }}')"  class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
            <form @submit.prevent="submit" action="{{ isset($album) && isset($image) ? route('admin.albums.media.update', [$album->id, $image->id]) : route('admin.albums.media.store', $album->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @isset($image)
                    @method('PUT')
                @endisset
                <div class="-mx-2.5 w-full">
                    <!-- Title -->
                    <div class="w-full px-2.5 mb-4">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            @lang('Image Title') <span class="text-red-500">*</span>
                        </label>
                        <input name="title" x-model="title" value="" type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <p x-show="$v.title.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">
                            @lang('Please enter a valid Title')
                        </p>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload file -->
                    <div x-data="fileUploadPreview('{{ isset($image) ? $image->getUrl() : '' }}')" class="w-1/2 px-2.5 mb-5">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            @lang('Upload file') <span x-show="!fileData" class="text-red-500">*</span>
                        </label>
                        <input @change="handleImageUpload($event)" type="file" accept=".jpg, .jpeg, image/jpeg" name="file" class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400">
                        <p x-show="($v.fileData.$invalid && $v.$touch) || error" class="text-red-500 text-sm mt-1">
                            @lang('Please enter a valid File. Only JPG/JPEG allowed')
                        </p>
                        @error('file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div x-show="fileData" class="text-center mt-3 bg-white shadow-md rounded-lg p-2 inline-block">
                            <h1 class="mb-2 text-sm font-medium">@lang('File Upload Preview')</h1>
                            <img :src="fileData" alt="preview image" class="rounded-lg max-w-[500px] max-h-[500px] object-contain">
                        </div>
                    </div>
                    <div class="px-2.5 mb-5">
                        <x-forms.checkbox-status
                            name="active"
                            label="{{__('Active')}}"
                            :checked="($image?->getCustomProperty('active') ?? 1) == 1"
                        />
                    </div>
                    @if(isset($moveToAlbums) && $moveToAlbums?->count() > 0)
                    <div class="w-1/2 px-2.5 mb-5">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            @lang('Move To Album')
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select name="move_to_album" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden " :class="isOptionSelected &amp;&amp; 'text-gray-500 dark:text-gray-400'" @change="isOptionSelected = true">
                                <option value="" class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                    — @lang('Do not move') —
                                </option>
                                @foreach($moveToAlbums as $other)
                                    <option value="{{ $other->id }}" {{ old('move_to_album') == $other->id ? 'selected' : '' }}>
                                        {{ $other->title }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                              <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                              </svg>
                            </span>
                        </div>
                    </div>
                    @endif
                    <div class="w-full px-2.5">
                        <div class="mt-10 flex items-center gap-3">
                            <button type="submit" class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white">
                                @lang('Save Changes')
                            </button>

                            <a href="{{ route('admin.albums.media.index', $album->id) }}" class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                @lang('Cancel')
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
            Alpine.data('fileUploadPreview', (initialImage) => ({
                fileData: initialImage || null,
                error: null,

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (this.parent) this.parent.fileData = file;
                    this.fileData = file;

                    if (!file || !this.isValidImage(file)) {
                        this.fileData = null;
                        this.$dispatch('file-selected', null);
                        event.target.value = '';

                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = (e) => {
                        this.fileData = e.target.result;
                        this.error = null;
                    }

                    reader.readAsDataURL(file);
                    this.$dispatch('file-selected', file);
                },

                isValidImage(file) {
                    const allowedTypes = ['image/jpeg', 'image/jpg'];
                    const extension = file.name.split('.').pop().toLowerCase();
                    const allowedExtensions = ['jpg', 'jpeg'];

                    if (!allowedTypes.includes(file.type) || !allowedExtensions.includes(extension)) {
                        this.error = 'Only JPG/JPEG allowed';
                        return false;
                    }

                    return true;
                },
            }));

            Alpine.data('image_validate', (initialImage = null) => ({
                title: @json(old('title', $image?->getCustomProperty('title') ?? '')),
                fileData: initialImage ? initialImage : null,

                init() {
                    this.$validation(this);

                    this.$el.addEventListener('file-selected', (e) => {
                        this.fileData = e.detail;
                    });
                },
                validations: {
                    title: ['required', 'min:3'],
                    fileData: ['required'],
                },
                async submit() {
                    this.title = this.title.trim();
                    await this.$v.validate();

                    if (this.$v.title.$invalid || this.$v.fileData.$invalid) {
                        return;
                    }

                    this.$el.submit();
                }
            }));
        });
    </script>
@endpush


