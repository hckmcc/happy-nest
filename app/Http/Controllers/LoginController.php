<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        // Валидация
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Попытка входа
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true ,
                    'message' => 'Successfully logged in',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->intended(route('home'));
        }

        // Ошибка входа
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false ,
                'errors' => ['email' => ['Неверный email или пароль']]
            ], 422);
        }

        throw ValidationException::withMessages([
            'success' => false ,
            'email' => ['Неверный email или пароль'],
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Successfully logged out']);
        }

        return redirect('/home');
    }
}
