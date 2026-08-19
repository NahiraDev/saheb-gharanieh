{{-- A titled panel. Reuses the site's ornate frame so the panel and the menu
     share one visual vocabulary. --}}
@props(['title' => null, 'subtitle' => null, 'icon' => null, 'flat' => false])

<x-frame :flat="$flat" {{ $attributes->class('admin-card') }}>
    @if ($title)
        <div class="admin-card-head">
            @if ($icon)
                <span class="admin-card-icon" aria-hidden="true">
                    <x-icon.admin :name="$icon" class="h-4 w-4" />
                </span>
            @endif

            <div class="admin-card-titles">
                <h2 class="admin-card-title gold-text">{{ $title }}</h2>
                @if ($subtitle)
                    <p class="admin-card-sub">{{ $subtitle }}</p>
                @endif
            </div>

            @isset($action)
                <div class="admin-card-action">{{ $action }}</div>
            @endisset
        </div>

        <span class="admin-card-rule gold-line" aria-hidden="true"></span>
    @endif

    <div class="admin-card-body">{{ $slot }}</div>
</x-frame>
