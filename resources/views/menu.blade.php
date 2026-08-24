@php
    $cafeName = $settings['cafe_name'] ?? 'کافه صاحبقرانیه';
    $cafeLatin = $settings['cafe_name_latin'] ?? 'Saheb Gharaniyeh Cafe';
    $tagline = $settings['tagline'] ?? null;
    $address = $settings['address'] ?? null;
    $phone = $settings['phone'] ?? null;
    $hours = $settings['working_hours'] ?? null;
    $instagram = $settings['instagram'] ?? null;
@endphp

<x-layouts.app title="منو" :meta-description="$settings['intro'] ?? null">
    <div id="qr-menu-page" dir="rtl">
        <header class="qr-menu-header">
            <x-emblem class="qr-menu-mark" />
            <h1 class="qr-menu-title">{{ $cafeName }}</h1>
            <p class="qr-menu-latin" dir="ltr">{{ $cafeLatin }}</p>

            @if ($tagline)
                <p class="qr-menu-tagline">{{ $tagline }}</p>
            @endif
            @if ($address)
                <p class="qr-menu-address mt-1">{{ $address }}</p>
            @endif
            @if ($phone)
                <a class="qr-menu-phone" href="tel:{{ preg_replace('/\s+/', '', $phone) }}" dir="ltr">{{ $phone }}</a>
            @endif
        </header>

        @if ($hours || $instagram || $address)
            <details class="qr-info">
                <summary>
                    <span>اطلاعات کافه</span>
                    <span aria-hidden>⌄</span>
                </summary>
                <div class="qr-info__body space-y-3">
                    @if ($hours)
                        <div>
                            <strong class="block mb-1 text-xs text-stone-800">ساعات کاری</strong>
                            <p>{{ $hours }}</p>
                        </div>
                    @endif
                    @if ($address)
                        <div>
                            <strong class="block mb-1 text-xs text-stone-800">نشانی</strong>
                            <p>{{ $address }}</p>
                        </div>
                    @endif
                    @if ($instagram)
                        <a href="https://instagram.com/{{ ltrim($instagram, '@') }}" target="_blank" rel="noreferrer"
                           class="inline-flex rounded-full border border-stone-200 bg-white px-3 py-1.5 text-xs font-semibold text-stone-700">
                            اینستاگرام
                        </a>
                    @endif
                </div>
            </details>
        @endif

        <div class="qr-topbar topbar" id="topbar" data-scrolled="false">
            <div class="qr-topbar__inner">
                <p class="section-flag" id="section-flag" aria-live="polite">
                    <span class="flag-dot" aria-hidden="true"></span>
                    <span class="section-flag-text" id="section-flag-text">
                        {{ $categories->firstWhere('slug', $activeSection)?->shortName() ?? $categories->first()?->shortName() }}
                    </span>
                </p>

                <nav class="chips" id="section-chips" aria-label="دسته‌بندی‌های منو">
                    @foreach ($categories as $category)
                        <a href="#{{ $category->slug }}"
                           class="chip"
                           data-chip="{{ $category->slug }}"
                           aria-current="{{ ($activeSection ? $activeSection === $category->slug : $loop->first) ? 'true' : 'false' }}">
                            @if ($category->glyph)
                                <x-icon.glyph :name="$category->glyph" class="chip-glyph" />
                            @endif
                            {{ $category->shortName() }}
                        </a>
                    @endforeach
                </nav>
            </div>
            <span class="topbar-progress" id="topbar-progress" aria-hidden="true"></span>
        </div>

        <main class="qr-menu-main" id="menu-root" data-initial-section="{{ $activeSection }}">
            @forelse ($categories as $category)
                <section id="{{ $category->slug }}"
                         class="qr-section scroll-section"
                         data-section="{{ $category->slug }}"
                         data-section-name="{{ $category->shortName() }}"
                         aria-labelledby="heading-{{ $category->slug }}">

                    <header class="qr-section__heading reveal">
                        <x-icon.section :category="$category" class="qr-section__glyph" />
                        <div class="min-w-0">
                            <h2 id="heading-{{ $category->slug }}">{{ $category->name }}</h2>
                            @if ($category->subtitle)
                                <p>{{ $category->subtitle }}</p>
                            @endif
                        </div>
                    </header>

                    @if ($category->description)
                        <p class="mb-3 text-xs leading-7 text-stone-500">{{ $category->description }}</p>
                    @endif

                    @if ($category->activeProducts->isEmpty())
                        <div class="qr-product block text-center text-xs text-stone-500">
                            این بخش به‌زودی تکمیل می‌شود.
                        </div>
                    @else
                        <div class="qr-items">
                            @foreach ($category->activeProducts as $product)
                                <x-product-card :product="$product" :category="$category" :index="$loop->iteration" />
                            @endforeach
                        </div>

                        @if ($category->price && $category->isHookah())
                            <div class="qr-service">
                                <span class="text-xs text-stone-500">{{ $category->price_note ?? 'قیمت' }}</span>
                                <strong class="mr-2 text-sm text-stone-800">@price($category->price)</strong>
                                <span class="text-[10px] text-stone-400">تومان</span>
                            </div>
                        @endif

                        @if ($category->features->isNotEmpty())
                            <div class="mt-3 rounded-xl border border-stone-200 bg-white p-3">
                                <p class="mb-2 text-center text-[11px] font-bold text-stone-600">همراه با این سرویس</p>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach ($category->features as $feature)
                                        <div class="flex flex-col items-center gap-1 text-center">
                                            <x-icon.glyph :name="$feature->glyph" class="h-5 w-5 text-stone-500" />
                                            <span class="text-[9px] text-stone-500">{{ $feature->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </section>
            @empty
                <div class="qr-product block py-12 text-center text-xs text-stone-500">
                    منو در حال آماده‌سازی است.
                </div>
            @endforelse
        </main>

        <footer class="qr-footer">{{ $cafeName }}</footer>
    </div>
</x-layouts.app>
