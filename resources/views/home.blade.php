<x-layouts.app title="منوی دیجیتال" :meta-description="$settings['intro'] ?? null">
    <main class="menu-home">
        <header class="menu-home__brand">
            <x-emblem class="menu-home__mark" />
            <h1 class="menu-home__title">{{ $settings['cafe_name'] ?? 'کافه صاحبقرانیه' }}</h1>
            <p class="menu-brand__latin">{{ $settings['cafe_name_latin'] ?? 'Saheb Gharaniyeh Cafe' }}</p>
            <p class="menu-home__tagline">{{ $settings['tagline'] ?? 'قهوه، قلیان و شب‌های دلنشین' }}</p>
        </header>

        <section class="menu-home__categories" aria-labelledby="menu-home-heading">
            <h2 id="menu-home-heading" class="sr-only">دسته‌بندی‌های منو</h2>
            @foreach ($cards as $card)
                <a href="{{ route('menu.section', $card->slug) }}#{{ $card->slug }}" class="menu-home__category">
                    <x-icon.section :category="$card" class="menu-home__category-icon" />
                    <span class="menu-home__category-title">
                        {{ $card->cardTitle() }}
                        @if ($card->card_subtitle)
                            <span class="menu-home__category-subtitle">{{ $card->card_subtitle }}</span>
                        @endif
                    </span>
                    <x-icon.chevron dir="start" class="menu-home__arrow h-4 w-4" />
                </a>
            @endforeach
        </section>

        <div class="menu-home__all">
            <a href="{{ route('menu') }}">مشاهده منوی کامل</a>
        </div>
    </main>
</x-layouts.app>
