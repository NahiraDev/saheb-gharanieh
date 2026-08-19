{{-- Success and error notices. Rendered server-side so they work without JS;
     admin.js only adds the auto-dismiss timer and the close button's effect. --}}
@php
    $status = session('status');
    $failure = session('error');
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

<div class="admin-toasts" id="admin-toasts" role="status" aria-live="polite">
    @if ($status)
        <div class="admin-toast admin-toast--ok" data-toast>
            <x-icon.admin name="check" class="admin-toast-icon" />
            <p class="admin-toast-text">{{ $status }}</p>
            <button type="button" class="admin-toast-close" data-toast-close aria-label="بستن">
                <x-icon.admin name="close" class="h-3.5 w-3.5" />
            </button>
        </div>
    @endif

    @if ($failure)
        <div class="admin-toast admin-toast--bad" data-toast>
            <x-icon.admin name="warning" class="admin-toast-icon" />
            <p class="admin-toast-text">{{ $failure }}</p>
            <button type="button" class="admin-toast-close" data-toast-close aria-label="بستن">
                <x-icon.admin name="close" class="h-3.5 w-3.5" />
            </button>
        </div>
    @endif

    @if ($errors->any())
        {{-- The messages also sit under their own fields; this is the summary for
             someone who has scrolled past the top of a long form. --}}
        <div class="admin-toast admin-toast--bad" data-toast data-toast-sticky>
            <x-icon.admin name="warning" class="admin-toast-icon" />
            <div class="admin-toast-text">
                <p>{{ \App\Support\Persian::digits($errors->count()) }} مورد نیاز به اصلاح دارد:</p>
                <ul class="admin-toast-list">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="admin-toast-close" data-toast-close aria-label="بستن">
                <x-icon.admin name="close" class="h-3.5 w-3.5" />
            </button>
        </div>
    @endif
</div>
