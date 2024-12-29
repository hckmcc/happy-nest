<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Services\RabbitMQService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    private RabbitMQService $rabbitMQService;
    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15|unique:users',
        ]);
        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
        ]);
        // Событие регистрации
        event(new Registered($user));
        //Mail::to($validated['email'])->send(new WelcomeEmail($validated['name']));
        $data= [
            'recipientEmail' => $validated['email'],
            'recipientName' => $validated['name'],
        ];
        $this->rabbitMQService->send($data, 'welcome');
        // Автоматический вход
        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true ,
                'message' => 'Successfully registered',
                'redirect' => route('home')
            ]);
        }

        return redirect(route('home'));
    }
    // RegisterController
    protected function registered(Request $request, $user)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successfully registered'
            ]);
        }
        return redirect($this->redirectPath());
    }
}
