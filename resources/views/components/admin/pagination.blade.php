{{-- Paginator with Persian numerals. Simple prev / page-count / next: on a phone
     a full page-number strip would wrap, and the list is filtered anyway. --}}
@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="admin-pager" aria-label="صفحه‌بندی">
        @if ($paginator->onFirstPage())
            <span class="admin-pager-btn admin-pager-btn--off" aria-disabled="true">
                <x-icon.admin name="right" class="h-4 w-4" />
                <span>قبلی</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="admin-pager-btn" rel="prev">
                <x-icon.admin name="right" class="h-4 w-4" />
                <span>قبلی</span>
            </a>
        @endif

        <span class="admin-pager-count">
            صفحه {{ \App\Support\Persian::digits($paginator->currentPage()) }}
            از {{ \App\Support\Persian::digits($paginator->lastPage()) }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="admin-pager-btn" rel="next">
                <span>بعدی</span>
                <x-icon.admin name="right" class="h-4 w-4 rotate-180" />
            </a>
        @else
            <span class="admin-pager-btn admin-pager-btn--off" aria-disabled="true">
                <span>بعدی</span>
                <x-icon.admin name="right" class="h-4 w-4 rotate-180" />
            </span>
        @endif
    </nav>
@endif
