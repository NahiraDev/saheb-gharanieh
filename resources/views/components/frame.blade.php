{{-- Ornate panel: double gold rule + four arabesque corners. --}}
@props(['corners' => true, 'flat' => false])

<div {{ $attributes->class(['frame', 'frame-flat' => $flat]) }}>
    @if ($corners)
        <x-ornament.corner class="frame-corner frame-corner--tr" />
        <x-ornament.corner class="frame-corner frame-corner--tl" />
        <x-ornament.corner class="frame-corner frame-corner--br" />
        <x-ornament.corner class="frame-corner frame-corner--bl" />
    @endif

    {{ $slot }}
</div>
