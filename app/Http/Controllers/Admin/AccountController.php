<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/** «حساب مدیر» — where the default admin/admin123 login gets replaced. */
class AccountController extends Controller
{
    public function edit(): View
    {
        return view('admin.account', [
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    public function update(AccountRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $admin->update($request->attributesToSave());

        if ($request->filled('password')) {
            // A password change invalidates other sessions; keep this one signed in.
            $request->session()->regenerate();
        }

        return back()->with('status', $request->filled('password')
            ? 'نام کاربری و رمز عبور ذخیره شد.'
            : 'مشخصات حساب ذخیره شد.');
    }
}
