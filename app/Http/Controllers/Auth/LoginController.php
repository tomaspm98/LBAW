<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

use App\Models\Member;

class LoginController extends Controller
{

    
    // Display a login form.
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }

    // Handle an authentication attempt.
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'user_email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($this->isSpecialUser($credentials['user_email'])) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        $user = Member::where('user_email', $credentials['user_email'])->first();

        if ($user && $user->user_blocked) {
            return back()->withErrors(['email' => 'Your account is blocked.']);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) { // , $request->filled('remember'))
            $request->session()->regenerate();
 
            return redirect()->intended('/');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function isSpecialUser($email)
    {
        // Add logic to check if the user with the given email is the special user
        return $email === 'deleted@example.com';
    }

    // Log out the user from application.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    } 
}
