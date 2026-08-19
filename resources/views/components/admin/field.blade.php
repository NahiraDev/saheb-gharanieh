{{-- Label + control + hint + error, so every form row lines up the same way.
     The control itself is the slot: this component owns the chrome, not the input. --}}
@props(['label' => null, 'name' => null, 'hint' => null, 'required' => false, 'for' => null])

@php
    $id = $for ?? $name;
    $error = $name ? $errors->first($name) : null;
@endphp

<div {{ $attributes->class(['admin-field', 'admin-field--bad' => filled($error)]) }}>
    @if ($label)
        <label class="admin-label" @if ($id) for="{{ $id }}" @endif>
            {{ $label }}
            @if ($required)
                <span class="admin-label-req" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($error)
        <p class="admin-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="admin-hint">{{ $hint }}</p>
    @endif
</div>
