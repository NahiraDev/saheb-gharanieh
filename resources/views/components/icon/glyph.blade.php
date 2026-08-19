{{-- Hand-drawn glyph, picked by key. See App\Support\Glyph for the catalogue.
     Everything is line art on a 64 grid so the whole set reads as one hand.
     cup / glass / hookah live in their own files — the landing cards use them
     directly — so this component delegates rather than redrawing them. --}}
@props(['name' => null])

@php
    $glyph = \App\Support\Glyph::resolve($name);
@endphp

@if ($glyph === 'cup')
    <x-icon.cup {{ $attributes }} />
@elseif ($glyph === 'glass')
    <x-icon.glass {{ $attributes }} />
@elseif ($glyph === 'hookah')
    <x-icon.hookah {{ $attributes }} />
@else
    <svg viewBox="0 0 64 64" fill="none" aria-hidden="true" {{ $attributes }}>
        <g stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            @switch($glyph)

                {{-- ─── Drinks & service ─────────────────────────────────── --}}
                @case('tea-glass')
                    {{-- Persian estekan in its saucer. --}}
                    <path d="M24 22h16l-2.2 22a4.5 4.5 0 0 1-4.5 4h-2.6a4.5 4.5 0 0 1-4.5-4L24 22Z" />
                    <path d="M25.4 33.5h13.2" opacity=".55" />
                    <path d="M14 51h36" />
                    <path d="M18.5 47.5h27" opacity=".45" />
                    <path d="M31 18.5c0-2.4 2.6-3 2.6-5.4" opacity=".5" />
                    <path d="M42 24c0-2.6 3-3.2 3-5.8" opacity=".4" />
                    @break

                @case('teapot')
                    <path d="M18.5 33.5h27c0 10-6 18-13.5 18s-13.5-8-13.5-18Z" />
                    <path d="M25.5 33.5a6.5 6.5 0 0 1 13 0" opacity=".9" />
                    <path d="M32 27.5v-3.5" />
                    <path d="M45.5 36.5c4.4.6 7 3.4 8 8.4-2.6.2-4.6-.8-6-3" opacity=".85" />
                    <path d="M18.5 37c-4 .6-6 3-6 7 0 2.4 1 4.4 2.8 5.8" opacity=".8" />
                    <path d="M16 51.5h32" opacity=".6" />
                    <path d="M32 18.5c0-2.4 2.6-3 2.6-5.4" opacity=".5" />
                    @break

                @case('milk')
                    <path d="M24 14h16v6l4 7v25a3 3 0 0 1-3 3H23a3 3 0 0 1-3-3V27l4-7v-6Z" />
                    <path d="M24 20h16" opacity=".5" />
                    <path d="M20.5 34.5h23" opacity=".6" />
                    <path d="M26 40.5c2.5-1.4 5-1.4 7.5 0" opacity=".35" />
                    @break

                @case('coffee-bean')
                    <g transform="rotate(-22 26 27)">
                        <ellipse cx="26" cy="27" rx="8.5" ry="12" />
                        <path d="M26 15c-2.6 7.6-2.6 15.4 0 24" opacity=".7" />
                    </g>
                    <g transform="rotate(20 39 40)">
                        <ellipse cx="39" cy="40" rx="8.5" ry="12" opacity=".9" />
                        <path d="M39 28c-2.6 7.6-2.6 15.4 0 24" opacity=".6" />
                    </g>
                    @break

                @case('crown')
                    <path d="M15 43.5 12.5 21.5l9.5 6.5L32 15l10 13 9.5-6.5L49 43.5H15Z" />
                    <path d="M14 48.5h36" />
                    <path d="M22 36h20" opacity=".35" />
                    <circle cx="32" cy="11.5" r="2" />
                    <circle cx="11.5" cy="18.5" r="1.5" opacity=".8" />
                    <circle cx="52.5" cy="18.5" r="1.5" opacity=".8" />
                    @break

                {{-- ─── Fruit ────────────────────────────────────────────── --}}
                @case('apple')
                @case('apple-ice')
                    <path d="M22.6 27.4c2.9-2.9 6.4-3.4 9.4-1.8 3-1.6 6.5-1.1 9.4 1.8 4.3 4.2 4.6 12.7.7 19-2.4 3.9-6.2 6.4-10.1 6.4s-7.7-2.5-10.1-6.4c-3.9-6.3-3.6-14.8.7-19Z" />
                    <path d="M32 25.6V18" />
                    <path d="M32.8 20.2c1.9-4.2 5.6-5.9 9.3-5.3.2 4-2.6 7.4-6.5 7.9-1.3.2-2.3-.7-2.8-2.6Z" opacity=".8" />
                    @if ($glyph === 'apple-ice')
                        <path d="M50 12v11M44.8 14.9l10.4 5.2M55.2 14.9l-10.4 5.2" opacity=".75" />
                    @else
                        <path d="M25.5 33c-1.4 2.6-1.8 5.4-1.2 8.4" opacity=".4" />
                    @endif
                    @break

                @case('cherry')
                    <path d="M24.5 47.5a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15Z" />
                    <path d="M41 49.5a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15Z" opacity=".92" />
                    <path d="M24.5 32.5C24.5 24.5 30.5 17.5 45 14" />
                    <path d="M41 34.5c-1-6.5.5-13 4-20.5" opacity=".85" />
                    <path d="M45.5 14.5c3-3.8 7.6-4.6 11.4-2.6-.5 4.4-4.4 7.6-8.8 7.1-1.3-.2-2.2-1.7-2.6-4.5Z" opacity=".8" />
                    @break

                @case('berry')
                    <circle cx="21.5" cy="38.5" r="8" opacity=".7" />
                    <circle cx="42.5" cy="38.5" r="8" opacity=".7" />
                    <circle cx="32" cy="42" r="9.5" />
                    <path d="M32 42v-6.5M32 42l6-3.5M32 42l-6-3.5M32 42l4.5 5.5M32 42 27.5 47.5" opacity=".6" />
                    <path d="M32 26.5c-1-4 .4-7.4 4-10" opacity=".55" />
                    @break

                @case('strawberry')
                    <path d="M32 52.5c-7.6 0-13.8-7.7-13.8-16 0-6.7 6.2-11.2 13.8-11.2s13.8 4.5 13.8 11.2c0 8.3-6.2 16-13.8 16Z" />
                    <path d="M21.8 25.5c3.2-3.1 6.6-4.2 10.2-3.6 3.6-.6 7 .5 10.2 3.6-3.2 1.7-6.5 2.3-10.2 1.7-3.7.6-7 0-10.2-1.7Z" opacity=".85" />
                    <path d="M32 21.9V16" />
                    <circle cx="26.5" cy="36" r="1.1" fill="currentColor" stroke="none" opacity=".6" />
                    <circle cx="37.5" cy="36" r="1.1" fill="currentColor" stroke="none" opacity=".6" />
                    <circle cx="32" cy="43" r="1.1" fill="currentColor" stroke="none" opacity=".6" />
                    @break

                @case('lemon')
                    {{-- A slice: it has to read differently from the whole orange. --}}
                    <path d="M32 15a17 17 0 1 0 0 34 17 17 0 0 0 0-34Z" />
                    <path d="M32 20.5a11.5 11.5 0 1 0 0 23 11.5 11.5 0 0 0 0-23Z" opacity=".75" />
                    <path d="M32 32V20.5M32 32l10-5.7M32 32l10 5.7M32 32v11.5M32 32l-10 5.7M32 32l-10-5.7" opacity=".5" />
                    @break

                @case('orange')
                    <circle cx="32" cy="36" r="16.5" />
                    <path d="M32 19.5V16" />
                    <path d="M33 15.8c2.2-4.2 6.3-5.9 10.4-4.8-.5 4.4-4 7.7-8.4 7.6-1-.1-1.7-1-2-2.8Z" opacity=".85" />
                    <path d="M22.5 30c2.6-2.6 5.7-4.1 9.2-4.4" opacity=".45" />
                    @break

                @case('grape')
                    <circle cx="23" cy="33" r="4.6" />
                    <circle cx="32" cy="33" r="4.6" opacity=".92" />
                    <circle cx="41" cy="33" r="4.6" />
                    <circle cx="27.5" cy="41.5" r="4.6" opacity=".92" />
                    <circle cx="36.5" cy="41.5" r="4.6" />
                    <circle cx="32" cy="49.5" r="4.6" opacity=".92" />
                    <path d="M32 28.4V19" />
                    <path d="M32.8 20.5c2.6-4.2 6.8-5.6 11-4-.8 4.4-4.6 7.4-9 7-1.2-.2-1.9-1.2-2-3Z" opacity=".8" />
                    @break

                @case('melon')
                    <path d="M13 26h38c0 12.7-8.5 23-19 23S13 38.7 13 26Z" />
                    <path d="M16.5 31.5h31" opacity=".65" />
                    <path d="M21 26c0 8.6 2.6 16.5 6.5 22M43 26c0 8.6-2.6 16.5-6.5 22" opacity=".4" />
                    @break

                @case('watermelon')
                    <path d="M12.5 25.5C18 21 24.7 18.5 32 18.5s14 2.5 19.5 7L32 51 12.5 25.5Z" />
                    <path d="M16.8 29.5C21.2 26.2 26.3 24.5 32 24.5s10.8 1.7 15.2 5" opacity=".55" />
                    <circle cx="27" cy="33.5" r="1.2" fill="currentColor" stroke="none" opacity=".75" />
                    <circle cx="37" cy="33.5" r="1.2" fill="currentColor" stroke="none" opacity=".75" />
                    <circle cx="32" cy="40" r="1.2" fill="currentColor" stroke="none" opacity=".75" />
                    @break

                @case('pomegranate')
                    <circle cx="32" cy="37" r="15" />
                    <path d="M25.5 24.5 27 15l3.5 7L32 14l1.5 8L37 15l1.5 9.5" opacity=".9" />
                    <circle cx="28" cy="36" r="1.3" fill="currentColor" stroke="none" opacity=".6" />
                    <circle cx="36" cy="36" r="1.3" fill="currentColor" stroke="none" opacity=".6" />
                    <circle cx="32" cy="43" r="1.3" fill="currentColor" stroke="none" opacity=".6" />
                    @break

                {{-- ─── Sweets & spice ───────────────────────────────────── --}}
                @case('ice-cream')
                    <circle cx="26.5" cy="25" r="7" />
                    <circle cx="38" cy="26.5" r="6" opacity=".9" />
                    <path d="M20.5 33.5h23L34 56.2a2.3 2.3 0 0 1-4 0L20.5 33.5Z" />
                    <path d="M24.5 40h15" opacity=".45" />
                    @break

                @case('lollipop')
                    <circle cx="32" cy="26" r="12.5" />
                    <path d="M32 38.5V54" />
                    <path d="M22.5 30.5c3.4-8 11.2-12 19.5-10" opacity=".6" />
                    <path d="M41.5 33c-3.4 6.8-9.8 10.4-17 9.5" opacity=".45" />
                    <path d="M28 54h8" opacity=".7" />
                    @break

                @case('cream')
                    <path d="M19.5 30.5h25c0 12.5-5.6 21-12.5 21s-12.5-8.5-12.5-21Z" />
                    <path d="M14 51.5h36" />
                    <path d="M32 30.5c0-3.2 2.3-5 5-4.2" opacity=".7" />
                    <circle cx="38.5" cy="24" r="3" opacity=".85" />
                    <path d="M24 36c1.4 2.8 1.4 5.4 0 8" opacity=".35" />
                    @break

                @case('pastry')
                    <path d="M32 15 52 32 32 49 12 32 32 15Z" />
                    <path d="M22 23.5 42 40.5M42 23.5 22 40.5" opacity=".5" />
                    <circle cx="32" cy="32" r="2.6" fill="currentColor" stroke="none" opacity=".75" />
                    @break

                @case('chocolate')
                    <path d="M16 19h32v26H16V19Z" />
                    <path d="M16 27.7h32M16 36.3h32M26.7 19v26M37.3 19v26" opacity=".45" />
                    <path d="M20 49.5h24" opacity=".6" />
                    @break

                @case('cinnamon')
                    <g transform="rotate(-9 24.5 32)">
                        <rect x="20" y="13" width="9" height="38" rx="4.5" />
                        <path d="M20 21c3 1.7 6 1.7 9 0" opacity=".5" />
                    </g>
                    <g transform="rotate(8 37.5 34)">
                        <rect x="33" y="17" width="9" height="34" rx="4.5" opacity=".9" />
                        <path d="M33 25c3 1.7 6 1.7 9 0" opacity=".45" />
                    </g>
                    @break

                @case('saffron')
                    <path d="M32 47c-1-9 1.4-16.6 7.4-22.6" />
                    <path d="M32 47c-4.5-7.8-5.4-15.6-2.8-23.4" opacity=".85" />
                    <path d="M32 47c3-8.5 8.4-14.5 16.4-18" opacity=".65" />
                    <path d="M24 47h16c0 2.8-3.6 5-8 5s-8-2.2-8-5Z" />
                    @break

                @case('mint')
                    <path d="M32 52V31" />
                    <path d="M31 34.5c-8.5 1.5-14.5-2.5-16-11 8.5-1.5 14.5 2.5 16 11Z" />
                    <path d="M33 30.5c8.5-3 15-.5 17.5 7.5-8.5 3-15 .5-17.5-7.5Z" opacity=".92" />
                    <path d="M21.5 27.5c3.4 2 6.2 4.4 9 7M45.5 34.5c-3.8.6-7.4 1.6-11 3.2" opacity=".45" />
                    @break

                @case('rose')
                    <circle cx="32" cy="29" r="12" />
                    <path d="M32 41c-6.6 0-12-5.4-12-12 0-4.7 3.8-8.5 8.5-8.5 3.6 0 6.5 2.9 6.5 6.5 0 2.6-2.1 4.7-4.7 4.7" opacity=".8" />
                    <path d="M32 41v11" />
                    <path d="M32 46c-4-2.6-8-2.4-11.5.6 2.6 3.9 6.8 4.9 11.5 2.4Z" opacity=".75" />
                    @break

                @case('leaf')
                    <path d="M50 13C28 13 15.5 23.5 15.5 37.5c0 5 2 9 5 11.5C36 47 50 34 50 13Z" />
                    <path d="M20.5 49C25.6 37.4 33.4 28.6 44 22" opacity=".55" />
                    @break

                {{-- ─── Marks & tools ────────────────────────────────────── --}}
                @case('moon')
                    <path d="M32 8a16 16 0 0 0 24 24 24 24 0 1 1-24-24Z" />
                    <path d="M47 42.5q1 4.5 5 5.5-4 1-5 5.5-1-4.5-5-5.5 4-1 5-5.5Z" opacity=".7" />
                    @break

                @case('heart')
                    <path d="M32 51S13 39.5 13 27.5C13 21.7 17.7 17 23.5 17c3.6 0 6.8 1.8 8.5 4.6 1.7-2.8 4.9-4.6 8.5-4.6C46.3 17 51 21.7 51 27.5 51 39.5 32 51 32 51Z" />
                    <path d="M21.5 25c-1.6 1.1-2.6 2.9-2.6 4.9" opacity=".5" />
                    @break

                @case('palace')
                    {{-- The pointed iwan of the Saheb Gharaniyeh pavilion. --}}
                    <path d="M13 52V32.5L32 12l19 20.5V52" />
                    <path d="M24 52V37l8-8.5 8 8.5v15" />
                    <path d="M10 52h44" />
                    <path d="M32 12V6" />
                    <circle cx="32" cy="4" r="1.5" />
                    <path d="M17 44h4M43 44h4" opacity=".4" />
                    @break

                @case('flame')
                    <path d="M32 53c-7.2 0-13-5.4-13-12.5 0-9.5 9-13.5 9-22.5 4 2 6 5.5 6 9 2-2.5 3-5 3-8 6 5.5 8 13 8 21.5C45 47.6 39.2 53 32 53Z" />
                    <path d="M32 53c-3.9 0-7-3-7-6.9 0-5.4 5-7.3 5-13 4.5 3 7 7.6 7 13 0 3.9-1.1 6.9-5 6.9Z" opacity=".55" />
                    @break

                @case('ice')
                    <path d="M32 13.5 50.5 21.5 32 29.5 13.5 21.5 32 13.5Z" />
                    <path d="M13.5 21.5v20.5L32 50.5l18.5-8.5V21.5" />
                    <path d="M32 29.5v21" opacity=".5" />
                    <path d="M19.5 26.5l7 3M44 30.5l-6 2.6" opacity=".35" />
                    @break

                @case('water')
                    <path d="M32 12c7 9 13 15.6 13 23.5C45 44 39 50 32 50s-13-6-13-14.5C19 27.6 25 21 32 12Z" />
                    <path d="M25.5 38c0 4 2.6 6.8 6.5 7.5" opacity=".5" />
                    @break

                @case('fruit-plate')
                    <circle cx="26" cy="26.5" r="6" />
                    <circle cx="38" cy="28" r="5" opacity=".9" />
                    <path d="M14 34h36c0 7.7-6.3 14-14 14h-8c-7.7 0-14-6.3-14-14Z" />
                    <path d="M32 48v4.5M24 52.5h16" />
                    <path d="M26 20.5c0-2.4 2.4-3 4.5-1.5" opacity=".6" />
                    @break

                @case('foil')
                    <path d="M20 21.5h24a4.5 4.5 0 0 1 0 9H20a4.5 4.5 0 0 1 0-9Z" />
                    <ellipse cx="20" cy="26" rx="2.6" ry="4.5" opacity=".8" />
                    <path d="M22.5 30.5 19.5 49h25l-3-18.5" opacity=".85" />
                    <path d="M27 34.5 26 46.5M37 34.5l1 12" opacity=".35" />
                    @break

                @case('tongs')
                    <path d="M29 16c-5 9-7.5 19-7 30 .2 3.4.8 6 2 8" />
                    <path d="M35 16c5 9 7.5 19 7 30-.2 3.4-.8 6-2 8" />
                    <path d="M29 16a3.5 3.5 0 0 1 6 0" />
                    <path d="M24 49l3 3.5M40 49l-3 3.5" opacity=".5" />
                    @break

                @case('wipe')
                    <path d="M18 19h28v26a2.5 2.5 0 0 1-2.5 2.5h-23A2.5 2.5 0 0 1 18 45V19Z" />
                    <path d="M18 19l4-4h20l4 4" opacity=".8" />
                    <path d="M32 26.5c3 4 5 6.7 5 9.3 0 3-2.2 5.2-5 5.2s-5-2.2-5-5.2c0-2.6 2-5.3 5-9.3Z" opacity=".65" />
                    @break

                {{-- The rosette: the default mark, and what an unknown key draws. --}}
                @default
                    <path d="M32 9q2 21 23 23-21 2-23 23-2-21-23-23 21-2 23-23Z" />
                    <path d="M48.5 11q1 3.5 4.5 4.5-3.5 1-4.5 4.5-1-3.5-4.5-4.5 3.5-1 4.5-4.5Z" opacity=".6" />
            @endswitch
        </g>
    </svg>
@endif
