@php
    $settings = \App\Models\Setting::map();
@endphp

<footer class="relative mt-14 pb-10">
    <x-ornament.divider class="mb-6 px-6" />

    <div class="mx-auto flex max-w-md flex-col items-center gap-3 px-6 text-center">
        <x-emblem class="w-24 text-gold-400/70" />

        <p class="latin text-[0.6875rem] text-gold-300/80">
            {{ $settings['cafe_name_latin'] ?? 'Saheb Gharaniyeh Cafe' }}
        </p>

        @if ($hours = $settings['working_hours'] ?? null)
            <p class="text-xs text-cream-dim">{{ $hours }}</p>
        @endif

        @if ($address = $settings['address'] ?? null)
            <p class="text-xs text-cream-dim/80">{{ $address }}</p>
        @endif

        <div class="mt-1 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs">
            @if ($phone = $settings['phone'] ?? null)
                <a href="tel:{{ \App\Support\Persian::western($phone) }}"
                   class="text-gold-200 transition hover:text-gold-100">{{ $phone }}</a>
            @endif

            @if ($instagram = $settings['instagram'] ?? null)
                <a href="https://instagram.com/{{ $instagram }}" target="_blank" rel="noopener" dir="ltr"
                   class="latin text-[0.625rem] text-gold-300/90 transition hover:text-gold-100">
                    {{ '@'.$instagram }}
                </a>
            @endif
        </div>

        <p class="mt-3 text-[0.625rem] text-cream-dim/60">
            تمام حقوق برای کافه صاحبقرانیه محفوظ است.
        </p>
    </div>
</footer>
