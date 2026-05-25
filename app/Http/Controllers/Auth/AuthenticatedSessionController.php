<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Log the login event
        AuditLog::create([
            'user_id'     => $user->id,
            'action'      => 'logged_in',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'description' => 'User logged in',
        ]);

        return match ($user->role) {
            'customer' => redirect()->route('dashboard'),
            default    => redirect()->route('staff.dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log the logout event before the session is cleared
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'logged_out',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'description' => 'User logged out',
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}