{{-- Theme switch, pinned to the top-right corner of every page.
     Dark is the house theme; the choice is remembered in localStorage. --}}
<button type="button"
        class="theme-toggle"
        id="theme-toggle"
        aria-label="تغییر پوسته روشن و تاریک"
        aria-pressed="false"
        title="پوسته روشن">
    <span class="theme-toggle-icon theme-toggle-icon--sun" aria-hidden="true">
        <x-icon.sun />
    </span>
    <span class="theme-toggle-icon theme-toggle-icon--moon" aria-hidden="true">
        <x-icon.moon />
    </span>
</button>
