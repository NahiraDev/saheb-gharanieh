{{-- Upload / preview / replace / remove, in one square that matches the menu
     card's placeholder so the owner sees the shape they are filling.

     `remove_image` is a hidden flag rather than a separate request: clearing the
     photo and saving the rest of the form should be one action, not two. --}}
@props(['product'])

@php
    $existing = $product->imageUrl();
    $error = $errors->first('image');
    $megabytes = \App\Support\UploadLimit::megabytesLabel();
@endphp

{{-- The size cap is written into the markup so admin.js can turn a photo away
     before the upload starts, rather than after a slow round trip. --}}
<div @class(['admin-image', 'admin-image--bad' => filled($error)])
     data-image-field
     data-image-max-bytes="{{ \App\Support\UploadLimit::bytes() }}"
     data-image-max-label="{{ $megabytes }}">
    <input type="hidden" name="remove_image" value="0" data-image-remove-flag>

    <div class="admin-image-frame" data-image-frame>
        {{-- Shown when a file is picked; src is filled in by admin.js. --}}
        <img class="admin-image-preview" data-image-preview alt="" hidden>

        @if ($existing)
            <img class="admin-image-current" src="{{ $existing }}" alt="{{ $product->name }}" data-image-current>
        @else
            <span class="admin-image-empty" data-image-empty>
                <x-icon.admin name="image" class="h-7 w-7" />
                <span>بدون تصویر</span>
            </span>
        @endif
    </div>

    <div class="admin-image-controls">
        <label class="admin-btn admin-btn--ghost admin-image-pick">
            <x-icon.admin name="upload" class="h-4 w-4" />
            <span data-image-pick-label>{{ $existing ? 'تصویر جدید' : 'انتخاب تصویر' }}</span>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/avif"
                   class="admin-image-input" data-image-input>
        </label>

        <button type="button" class="admin-btn admin-btn--quiet admin-image-clear"
                data-image-clear @unless ($existing) hidden @endunless>
            <x-icon.admin name="trash" class="h-4 w-4" />
            <span>حذف تصویر</span>
        </button>
    </div>

    {{-- This field is not wrapped in x-admin.field, so it has to show its own
         message: without this the photo was refused in silence. --}}
    <p class="admin-error admin-image-error" data-image-error @unless ($error) hidden @endunless>{{ $error }}</p>

    <p class="admin-hint">JPG، PNG، WEBP یا AVIF — تا {{ $megabytes }} مگابایت. تصویر مربعی بهترین نتیجه را می‌دهد.</p>
</div>
