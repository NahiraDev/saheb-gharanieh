{{-- Choose the little hand-drawn mark shown beside an item or a section.

     Native radios inside a <details>: it collapses to one row on a phone, needs
     no JS to work, and the swatch grid doubles as a preview of the whole set. --}}
@props(['name' => 'glyph', 'groups', 'selected' => null, 'label' => 'نقش'])

@php
    $current = old($name, $selected);
    $currentLabel = \App\Support\Glyph::label($current);
@endphp

<details {{ $attributes->class('admin-glyphs') }} data-glyph-picker>
    <summary class="admin-glyphs-summary">
        <span class="admin-glyphs-preview" data-glyph-preview>
            @if ($current)
                <x-icon.glyph :name="$current" class="h-5 w-5" />
            @else
                <x-icon.admin name="sparkle" class="h-4 w-4 opacity-50" />
            @endif
        </span>

        <span class="admin-glyphs-summary-text">
            <span class="admin-glyphs-label">{{ $label }}</span>
            <span class="admin-glyphs-value" data-glyph-value>{{ $currentLabel ?? 'بدون نقش' }}</span>
        </span>

        <x-icon.admin name="down" class="admin-glyphs-chevron" />
    </summary>

    <div class="admin-glyphs-body">
        <div class="admin-glyphs-grid">
            <label class="admin-glyph-option" title="بدون نقش">
                <input type="radio" name="{{ $name }}" value="" @checked(blank($current))
                       data-glyph-option data-glyph-name="بدون نقش">
                <span class="admin-glyph-swatch">
                    <x-icon.admin name="close" class="h-4 w-4" />
                </span>
                <span class="admin-glyph-caption">بدون نقش</span>
            </label>
        </div>

        @foreach ($groups as $groupLabel => $glyphs)
            <p class="admin-glyphs-group">{{ $groupLabel }}</p>

            <div class="admin-glyphs-grid">
                @foreach ($glyphs as $key => $glyphLabel)
                    <label class="admin-glyph-option" title="{{ $glyphLabel }}">
                        <input type="radio" name="{{ $name }}" value="{{ $key }}"
                               @checked($current === $key)
                               data-glyph-option data-glyph-name="{{ $glyphLabel }}">
                        <span class="admin-glyph-swatch">
                            <x-icon.glyph :name="$key" class="h-6 w-6" />
                        </span>
                        <span class="admin-glyph-caption">{{ $glyphLabel }}</span>
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>
</details>
