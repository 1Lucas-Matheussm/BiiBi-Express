<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'document' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:cliente,entregador,empresa'],

            'vehicle_type' => ['nullable', 'required_if:role,entregador', 'string', 'max:20'],
            'plate' => ['nullable', 'required_if:role,entregador', 'string', 'max:10'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'company_name' => ['nullable', 'required_if:role,empresa', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),

            'phone' => $request->phone,
            'document' => $request->document,
            'address' => $request->address,
            'role' => $request->role,
            'email_verified' => false,

            'vehicle_type' => $request->vehicle_type,
            'plate' => $request->plate,
            'rating' => $request->rating,
            'company_name' => $request->company_name,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
