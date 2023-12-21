<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

use Illuminate\View\View;

use App\Models\Member;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\FileController;

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
    try {
        $validator = $request->validate([
            'username' => 'required|string|max:255|unique:member',
            'user_email' => 'required|string|email|max:255|unique:member',
            'password' => 'required|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[.@$!%*#?&]/',
            //Password includes at least one lowercase letter, one uppercase letter, one number, and one special character respectively.
            'picture' => 'nullable|image|mimes:png|max:10240',
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
            'password.string' => 'The password must be text.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character (.@$!%*#?&).',
            
            'picture.image' => 'The uploaded file must be an image.',
            'picture.mimes' => 'Only PNG format is allowed.',
            'picture.max' => 'The file size must not exceed 10 megabytes.',

            'user_birthdate.required' => 'The birthdate field is required.',
            'user_birthdate.date' => 'The birthdate must be a valid date.',
            'user_birthdate.before_or_equal' => 'You must be at least 12 years old to register.',
        ]);
        //Register the profile picture either with default of the one from request
        
        if ($request->hasFile('picture')) {

            $username = $request->input('username');
            $filename = 'profile_' . $username . '.png';

            $fileController = new FileController();
            $fileController->upload($request);
        } else {
            $profilePicture = 'default_profile.png';
            $request->merge(['picture' => $profilePicture]);
        }
    
        Member::create([
            'username' => $request->username,
            'user_email' => $request->user_email,
            'password' => Hash::make($request->password),
            'picture' => $filename,
            'user_birthdate' => Carbon::parse($request->user_birthdate)->toDateTimeString()
        ]);

        $credentials = $request->only('user_email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->withSuccess('You have successfully registered and logged in!');
        } else {
            Log::info("Authentication failed");
            return redirect()->route('register')
                ->withErrors(['authentication' => 'Registration failed in the process. Please check your credentials and try again.'])
                ->withInput();
        }
    } catch (ValidationException $e) {
        Log::info("Validation failed with errors:", $e->validator->errors()->toArray());
        return redirect()->route('register')
            ->withErrors($e->validator->errors())
            ->withInput();
    }
    }
}
