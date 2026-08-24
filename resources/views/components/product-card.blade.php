@props(['product', 'category', 'index' => 1])

@php($image = $product->imageUrl())

<article class="qr-product reveal"
         style="--reveal-delay: {{ min($index * 45, 360) }}ms"
         @if (! $product->is_available) data-unavailable="true" @endif>
    <div class="qr-product__content">
        <div class="flex flex-wrap items-center gap-1.5">
            <h3 class="qr-product__title">{{ $product->name }}</h3>
            @if ($product->is_featured)
                <span class="qr-product__badge">پیشنهاد</span>
            @endif
        </div>

        @if ($product->latin_name)
            <p class="qr-product__latin">{{ $product->latin_name }}</p>
        @endif

        @if ($product->description)
            <p class="qr-product__description">{{ $product->description }}</p>
        @endif

        <div class="qr-product__bottom">
            @if ($product->price)
                <strong class="qr-product__price">@price($product->price)</strong>
                <span class="qr-product__unit">تومان</span>
            @else
                <span class="qr-product__unit">قیمت در محل</span>
            @endif

            @if (! $product->is_available)
                <span class="qr-product__unavailable">موقتاً ناموجود</span>
            @endif
        </div>
    </div>

    @if ($image)
        <div class="qr-product__media">
            <img src="{{ $image }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 decoding="async"
                 data-fade-in
                 onerror="this.closest('.qr-product__media')?.remove()">
        </div>
    @else
        <div class="qr-product__media qr-product__media--empty" aria-hidden="true">
            <x-icon.glyph :name="$product->glyphKey()" class="h-7 w-7" />
        </div>
    @endif
</article>
