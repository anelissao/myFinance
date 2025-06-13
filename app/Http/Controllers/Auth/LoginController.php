<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log the connection
            Connection::create([
                'user_id' => Auth::id(),
                'login_time' => now(),
                'ip_address' => $request->ip(),
            ]);

            // Redirect based on user role
            $user = Auth::user();
            switch ($user->role) {
                case 'ADMIN':
                    return redirect()->intended(route('admin.dashboard'));
                case 'CONSEILLER_FINANCIER':
                    return redirect()->intended(route('advisors.dashboard'));
                default:
                    return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 