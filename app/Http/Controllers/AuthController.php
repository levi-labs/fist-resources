<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        $title = 'Login';
        return view('layouts.auth.login', compact('title'));
    }

    public function login(AuthRequest $request)
    {

        $credentials = $request->only('username', 'password');
        if (auth('web')->attempt($credentials)) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    }

    public function logout()
    {
        auth('web')->logout();
        return redirect()->route('auth.login')->with('success', 'You are logged out');
    }
}
