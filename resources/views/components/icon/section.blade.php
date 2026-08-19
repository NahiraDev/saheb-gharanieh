{{-- The glyph that stands for a section: the one chosen in the admin panel, or a
     sensible guess from the section itself. Used by the landing cards, the menu
     headings and the empty photo slots. --}}
@props(['category'])

<x-icon.glyph :name="\App\Support\Glyph::forCategory($category)" {{ $attributes }} />
