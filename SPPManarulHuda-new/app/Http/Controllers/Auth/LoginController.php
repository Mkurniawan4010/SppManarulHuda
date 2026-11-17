<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest', [
            'except' => [
                'logout'
            ]
        ]);
    }

    public function showLoginForm()
    {
        return View::make('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return Redirect::intended('/dashboard');
        }

        // Generate a fresh token for the next request
        $request->session()->regenerateToken();

        return Redirect::back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'Username atau Password Salah',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login')
            ->with('success', 'You have been successfully logged out.');
    }
} 

