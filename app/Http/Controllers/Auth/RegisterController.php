<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\View\View;

use App\Models\Member;

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
        $request->validate([
            'username' => 'required|string|max:255|unique:member',
            'user_email' => 'required|string|email|max:255|unique:member',
            'password' => 'required|string|min:8|confirmed',
            'picture' => 'nullable|image|mimes:png,jpeg,svg|max:10240',
            'user_birthdate' => 'required|date|before_or_equal:' . now()->subYears(12)->format('Y-m-d')
        ],
        [ // Custom error messages
            'username.required' => 'The username field is required.',
            'username.string' => 'The username must be a string.',
            'username.max' => 'The username must not exceed 255 characters.',
            'username.unique' => 'The username is already taken.',
            
            'user_email.required' => 'The email field is required.',
            'user_email.string' => 'The email must be a string.',
            'user_email.email' => 'The email must be a valid email address.',
            'user_email.max' => 'The email must not exceed 255 characters.',
            'user_email.unique' => 'The email is already taken.',
            
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            
            'picture.image' => 'The uploaded file must be an image.',
            'picture.mimes' => 'Only PNG, JPEG, and SVG formats are allowed.',
            'picture.max' => 'The file size must not exceed 10 megabytes.',

            'user_birthdate.required' => 'The birthdate field is required.',
            'user_birthdate.date' => 'The birthdate must be a valid date.',
            'user_birthdate.before_or_equal' => 'The birthdate must be at least 12 years ago.',
        ]);

        if ($request->hasFile('picture')) {

            $username = $request->input('username');
            $fileExtension = $request->file('picture')->getClientOriginalExtension();

            // Save the image to a storage disk within a folder named after the username
            $filename = 'profile_picture.' . $fileExtension;

            $path = $request->file('picture')->storeAs("public/pictures/{$username}", $filename);

            // Update the 'picture' field with the path
            $request->merge(['picture' => $path]);
        } else {
            // No picture provided, set a default value
            $request->merge(['picture' => 'storage/app/public/pictures/default/profile_picture.png']);
        }

        Member::create([
            'username' => $request -> username,
            'user_email' => $request -> user_email,
            'password' => Hash::make($request -> password),
            'picture' => $path,
            'user_birthdate' => Carbon::parse($request->user_birthdate)->toDateTimeString()
        ]);
        $credentials = $request->only('user_emailAuth::attempt($credentials);', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('/home')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
