<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
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
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15|unique:users',
        ];

        if ($request->is('admin/*')) {
            $validationRules['roles'] = 'nullable|array';
            $validationRules['roles.*'] = 'exists:roles,id';
        }

        $validated = $request->validate($validationRules);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
        ]);

        if ($request->is('admin/*')) {
            if (!empty($validated['roles'])) {
                $user->roles()->attach($validated['roles']);
            }else{
                $userRole = Role::where('slug', 'user')->first();
                if ($userRole) {
                    $user->roles()->attach($userRole->id);
                }
            }
            $redirectRoute = 'admin.users';
        } else {
            $userRole = Role::where('slug', 'user')->first();
            if ($userRole) {
                $user->roles()->attach($userRole->id);
            }
            $redirectRoute = 'home';
        }
        if (!$request->is('admin/*')) {
            Auth::login($user);
        }
        // Событие регистрации
        event(new Registered($user));
        //Mail::to($validated['email'])->send(new WelcomeEmail($validated['name']));
        $data= [
            'recipientEmail' => $validated['email'],
            'recipientName' => $validated['name'],
        ];
        $this->rabbitMQService->send($data, 'welcome');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true ,
                'message' => 'Successfully registered',
                'redirect' => route($redirectRoute)
            ]);
        }

        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Регистрация успешно завершена');
    }
}
