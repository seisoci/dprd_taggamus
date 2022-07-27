<!-- start page title -->
<ol class="breadcrumb">
    @foreach($page_breadcrumbs as $item)
        @if($loop->last)
            @php
                $active = ' active';
            @endphp
        @endif
        <li class="breadcrumb-item{{ $active ?? "" }}" {{ isset($active) ? "aria-current='page'" : '' }}>
            @if(!$loop->last)
                <a href={{ $item['url'] ?? "#" }}>{{ $item['title'] ?? '' }}</a>
            @else
                {{ $item['title' ?? ''] }}
            @endif
        </li>
    @endforeach
</ol>
<!-- end page title -->
