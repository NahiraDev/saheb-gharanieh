{{-- Nothing-here notice: a small ornament, a line of explanation, an optional
     way forward. --}}
@props(['title', 'text' => null, 'icon' => 'sparkle'])

<div {{ $attributes->class('admin-empty') }}>
    <span class="admin-empty-mark" aria-hidden="true">
        <x-icon.admin :name="$icon" class="h-6 w-6" />
    </span>

    <p class="admin-empty-title">{{ $title }}</p>

    @if ($text)
        <p class="admin-empty-text">{{ $text }}</p>
    @endif

    @isset($action)
        <div class="admin-empty-action">{{ $action }}</div>
    @endisset
</div>
