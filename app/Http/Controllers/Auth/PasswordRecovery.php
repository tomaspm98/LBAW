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
use Illuminate\Validation\ValidationException;
use App\Jobs\SendPasswordResetEmail;

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
    // Display the token recovery form.
    public function showTokenRecoveryForm(Request $request,$token)
    {   
        $userEmail = $request->session()->get('password_reset_email');
        $tokenFromSession = $request->session()->get('password_reset_token');
        if ($token !== $tokenFromSession) {
            abort(403, 'Invalid token');
        }
        if($token == session('password_reset_token')){
            return view('auth.verify_token', [
                'user_email' => $userEmail,
                'token' => $token,
                'session_token' => $token
            ]);
        }else{
            return redirect()->route('account-recovery')->withErrors(['token' => 'Something went wrong with the token redirect.']);
        }
    }
    // Display the password reset form.
    public function showPasswordResetForm(Request $request,$token)
    {
        Log::info('showPasswordResetForm');
        Log::info($token);
        Log::info(session('password_reset_token'));
        Log::info(session('password_reset_email'));

        $userEmail = $request->session()->get('password_reset_email');
        $tokenFromSession = $request->session()->get('password_reset_token');
        if ($token !== $tokenFromSession) {
            abort(403, 'Invalid token');
        }
        Log::info('showPasswordResetForm 2');
        if($token == session('password_reset_token')){
            return view('auth.change_password', [
                'user_email' => $userEmail,
                'token' => $token
            ]);
        }else{
            return redirect()->route('token-recovery',[$tokenFromSession])->withErrors(['token' => 'Something went wrong with the token redirect.']);
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
         

        // Send the email synchronously
        SendPasswordResetEmail::dispatch($emailToken, $request->user_email);

        $request->session()->put('password_reset_email', $request->user_email);
        $request->session()->put('password_reset_token', $secureToken);

        // Redirect to the verifyTokenForm route with the token as a parameter
        return redirect()->route('token-recovery',[$secureToken])->withSuccess('A reset token has been sent to your email!');
    }

    // Resend email verification link.
    public function resend_email(Request $request)
    {        
        //email cannot be empty without validate
        if($request->input('user_email') == null){
            return response()->json(['success'=> false ,'error' => 'The email field is required.']);
        }

        $request->session()->forget('password_reset_email');

        $user = Member::where('user_email', $request->input('user_email'))->exists();
        if (!$user) {
            return response()->json(['success'=> false ,'error' => 'Something went wrong.']);
        }

        // Unique token to be sent via email
        $emailToken = bin2hex(random_bytes(10));      
        // Token to be stored on the server
        

        DB::table('password_resets')->updateOrInsert([
            'user_email' => $request->user_email,
            'token' => Hash::make($emailToken),
        ]);

        // Send the email
        Mail::to($request->user_email)->send(new PasswordReset($emailToken));
        $request->session()->put('password_reset_email', $request->user_email);

        return response()->json(['success'=> true ,'message' => 'A reset token has been sent to your email! Please use the new token to reset your password.']);
    }
    
    public function verifyToken(Request $request)
    {
        try{
            // Validate the token
            $request->validate([
                'token' => ['required'],
            ], [
                'token.required' => 'The token field is required.',
            ]);
            $userEmail = $request->session()->get('password_reset_email');
            $storedToken = DB::table('password_resets')->where('user_email', $userEmail)->orderByDesc('created_at')->value('token');

            if (Hash::check($request->input('token'), $storedToken)) {
                Log::info('verifyToken');
                return redirect()->route('password-reset',[$request->input('session_token')]);
            } else {
                return redirect()->back()->withErrors(['token' => 'Invalid token']);
            }
        }catch(\Exception $e){
            return redirect()->back()->withErrors(['token' => 'Missing token']);
        }
    }
    public function resetPassword(Request $request)
    {
        try{
        $validator = $request->validate([
            'password' => ['required', 'confirmed', 'min:8','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/','regex:/[.@$!%*#?&]/'],
        ], [
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The passwords do not match.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character (.@$!%*#?&).',
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

        return redirect()->route('login')->withSuccess('Password changed successfully!');
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->validator->errors())
            ->withInput();
    }
    }
}
