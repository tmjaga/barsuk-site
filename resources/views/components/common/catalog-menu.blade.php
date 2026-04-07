@props(['categories' => [], 'currentCategory' => null])

@foreach($categories as $category)
    <div class="box-title {{ $category->slug == $currentCategory ? 'box-title-active' : '' }}">
        <a href="{{ route('catalog', ['slug' => $category->slug]) }}">
            {{ $category->title_localized }}
        </a>
    </div>
    <ul class="list-unstyled mb-0">
        @forelse($category->activeServices as $service)
            <li>
                <a href="#">{{ $service->title_localized }}</a>
            </li>
        @empty
        @endforelse
    </ul>
@endforeach
