<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        // Someone who is already signed in wants the panel, not the form.
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['username', 'password']);

        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // One message for both cases: a wrong-username reply would tell a
            // stranger which usernames exist.
            throw ValidationException::withMessages([
                'username' => 'نام کاربری یا رمز عبور درست نیست.',
            ]);
        }

        $request->session()->regenerate();

        Auth::guard('admin')->user()->forceFill(['last_login_at' => now()])->save();

        return redirect()
            ->intended(route('admin.dashboard'))
            ->with('status', 'خوش آمدید.');
    }

    public function destroy(): RedirectResponse
    {
        Auth::guard('admin')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('status', 'از پنل خارج شدید.');
    }
}
