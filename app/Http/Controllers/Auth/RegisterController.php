<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        Validator::make($request->all(), [
            'profile_photo' => 'nullable|image|max:1024',
            'username' => 'required|string|max:25',
            'user_email' => 'required|email|max:25|unique:member',
            'user_birthdate' => [
                'required',
                'date',
                'before:' . \Carbon\Carbon::now()->subYears(12)->format('Y-m-d')
            ],
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'user_email' => $request->email,
            'user_password' => Hash::make($request->password),
            'picture' => $request-> picture,
            'user_birthdate' => $request->birthdate
        ]);

        $credentials = $request->only('email', 'user_password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('/home')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
