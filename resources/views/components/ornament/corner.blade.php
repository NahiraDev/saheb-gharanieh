{{-- Arabesque corner flourish. Drawn for a top-right corner; the .frame-corner--*
     modifiers mirror it for the remaining three corners. --}}
@props(['class' => ''])

<svg viewBox="0 0 64 64" fill="none" aria-hidden="true" {{ $attributes->merge(['class' => $class]) }}>
    <g stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
        {{-- outer + inner corner rails --}}
        <path d="M62 47C62 22.7 43.3 4 19 4" stroke-width="1.4" opacity=".9" />
        <path d="M62 38.5C62 26.6 51.9 16.5 40 16.5" stroke-width=".9" opacity=".55" />

        {{-- curl closing the rail along the top edge --}}
        <path d="M19 4C11.8 4 6 7.6 3.6 13.2c-1.7 4 .6 8.3 4.9 8.3 3.2 0 5.6-2.4 5.6-5.6 0-2.6-1.8-4.6-4.1-4.9"
              stroke-width="1.1" />

        {{-- curl closing the rail along the right edge --}}
        <path d="M62 47c0 7.2-3.6 13-9.2 15.4-4 1.7-8.3-.6-8.3-4.9 0-3.2 2.4-5.6 5.6-5.6 2.6 0 4.6 1.8 4.9 4.1"
              stroke-width="1.1" />

        {{-- palmette leaves pointing into the panel --}}
        <path d="M44.5 19.5c-6.7.6-12.4 5.3-14.2 11.8 6.7-.6 12.4-5.3 14.2-11.8Z" stroke-width="1" />
        <path d="M35.6 12.8c-3.9 1.6-6.7 5-7.6 9.1 3.9-1.6 6.7-5 7.6-9.1Z" stroke-width=".9" opacity=".7" />
        <path d="M51.2 28.4c-1.6 3.9-5 6.7-9.1 7.6 1.6-3.9 5-6.7 9.1-7.6Z" stroke-width=".9" opacity=".7" />
    </g>

    <g fill="currentColor">
        <circle cx="24.5" cy="8.6" r="1.15" />
        <circle cx="55.4" cy="39.5" r="1.15" />
        <circle cx="34" cy="30" r=".9" opacity=".7" />
    </g>
</svg>
