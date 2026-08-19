{{-- Chevron. `dir` accepts up | down | start (RTL-aware “forward” arrow). --}}
@props(['dir' => 'up'])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
     stroke-linejoin="round" aria-hidden="true" {{ $attributes }}>
    @if ($dir === 'up')
        <path d="m6 15 6-6 6 6" />
    @elseif ($dir === 'down')
        <path d="m6 9 6 6 6-6" />
    @else
        {{-- points to the reading direction end (left in RTL) --}}
        <path d="m14 6-6 6 6 6" />
    @endif
</svg>
