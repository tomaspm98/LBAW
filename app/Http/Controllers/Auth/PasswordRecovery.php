<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\Member;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PasswordRecovery extends Controller
{
    // Display the account recovery form.
    public function showAccountRecoveryForm()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('auth.account-recovery');
        }
    }
    
    // Send password reset link.
    public function sendPasswordResetToken(Request $request)
    {
        $request->validate([
            'user_email' => ['required', 'email'],
        ], [
            'user_email.required' => 'The email field is required.',
            'user_email.email' => 'The email you indicated is not valid.',
        ]);
        $user = Member::where('user_email', $request->input('user_email'))->exists();
        if (!$user) {
            return redirect()->route('account-recovery')->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }
        
        // Unique token to be sent via email
        $emailToken = bin2hex(random_bytes(10));      

        // Token to be stored on the server
        $secureToken = bin2hex(random_bytes(50));


        // Store the  token in  database along with the user's email
        DB::table('password_resets')->insert([
            'user_email' => $request->user_email,
            'token' => Hash::make($emailToken),
        ]);
         

        // Send the email
        Mail::to($request->user_email)->send(new PasswordReset($emailToken));

        $request->session()->put('password_reset_email', $request->user_email);
        $request->session()->put('password_reset_token', $secureToken);

        // Redirect to the verifyTokenForm route with the token as a parameter
        return view('auth.verify_token')->with('status', 'Password reset link sent!');
    }
    
    // Verify token.
    /*public function verifyTokenForm(Request $request)
    {
        $token = $request->input('token');

        if ($request->session()->get('password_reset_token') === $token) {
            return view('auth.verify_token');
        } else {
            // If it doesn't match, redirect back or show an error page
            return redirect()->back()->withErrors(['token' => 'Invalid token']);
        }
    }*/
    public function verifyToken(Request $request){
        // Validate the token
        $request->validate([
            'token' => ['required'],
        ], [
            'token.required' => 'The token field is required.',
        ]);

        $userEmail = $request->session()->get('password_reset_email');
        $storedToken = DB::table('password_resets')->where('user_email', $userEmail)->orderByDesc('created_at')->value('token');

        if (Hash::check($request->input('token'), $storedToken)) {
            return view('auth.change_password');
        } else {
            return redirect()->back()->withErrors(['token' => 'Invalid token']);
        }
        
        
    }
    public function resetPassword(Request $request){
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The passwords do not match.',
            'password.min' => 'The password must be at least 8 characters.',
        ]);
        $request->validate([
            'password_confirmation' => ['required'],
        ], [
            'password_confirmation.required' => 'The password confirmation field is required.',
        ]);

        //Check if passwords are equal
        if ($request->input('password') !== $request->input('password_confirmation')) {
            return redirect()->back()->withErrors(['password' => 'The passwords do not match.']);
        }

        $userEmail = $request->session()->get('password_reset_email');
        $user = Member::where('user_email', $userEmail)->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        //Remove token from database
        DB::table('password_resets')->where('user_email', $userEmail)->delete();
        // Remove token and email from session
        $request->session()->forget('password_reset_token');
        $request->session()->forget('password_reset_email');

        return redirect()->route('login')->with('success', 'Password changed successfully!');
    }
}
