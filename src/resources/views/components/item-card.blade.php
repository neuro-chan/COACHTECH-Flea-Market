@props(['item'])

<a href="{{ route('items.show', $item) }}" class="item-card">
    <div class="item-image">
        @if ($item->is_sold)
            <span class="sold-label">Sold</span>
        @endif
        <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
    </div>
    <div class="item-name">
        {{ $item->title }}
    </div>
</a>
