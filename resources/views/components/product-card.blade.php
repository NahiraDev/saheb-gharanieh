@props(['product', 'category', 'index' => 1])

@php($image = $product->imageUrl())

<article
    class="menu-product{{ $image ? ' menu-product--image' : '' }}"
    @if (! $product->is_available) data-unavailable="true" @endif
>
    @if ($image)
        <div class="menu-product__media" aria-hidden="true">
            <img src="{{ $image }}" alt="" loading="lazy" decoding="async">
        </div>
    @endif

    <div class="menu-product__main">
        <h3 class="menu-product__name">{{ $product->name }}</h3>

        @if ($product->latin_name)
            <p class="menu-product__latin">{{ $product->latin_name }}</p>
        @endif

        @if ($product->description)
            <p class="menu-product__description">{{ $product->description }}</p>
        @endif

        @if (! $product->is_available)
            <p class="menu-product__description" style="color:#8C99D9">موقتاً تمام شد</p>
        @endif
    </div>

    <div class="menu-product__price">
        @if ($product->price)
            @price($product->price)
            <span class="menu-product__unit">تومان</span>
        @else
            <span class="menu-product__unit">قیمت در محل</span>
        @endif
    </div>
</article>
