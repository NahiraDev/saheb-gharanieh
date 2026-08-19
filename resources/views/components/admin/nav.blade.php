{{-- One nav definition, two presentations: pills in the top bar on a wide
     screen, a bottom tab bar on a phone. --}}
@php
    $links = [
        ['route' => 'admin.dashboard', 'label' => 'داشبورد', 'icon' => 'dashboard',
            'match' => ['admin.dashboard']],
        ['route' => 'admin.products.index', 'label' => 'موارد منو', 'icon' => 'items',
            'match' => ['admin.products.*']],
        ['route' => 'admin.categories.index', 'label' => 'دسته‌ها', 'icon' => 'categories',
            'match' => ['admin.categories.*', 'admin.features.*']],
        ['route' => 'admin.settings.edit', 'label' => 'متن‌ها', 'icon' => 'settings',
            'match' => ['admin.settings.*']],
        ['route' => 'admin.account.edit', 'label' => 'حساب', 'icon' => 'account',
            'match' => ['admin.account.*']],
    ];
@endphp

<nav {{ $attributes->class('admin-nav') }} aria-label="بخش‌های پنل">
    @foreach ($links as $link)
        <a href="{{ route($link['route']) }}"
           class="admin-nav-link"
           @if (request()->routeIs($link['match'])) aria-current="page" @endif>
            <x-icon.admin :name="$link['icon']" class="admin-nav-icon" />
            <span class="admin-nav-label">{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>
