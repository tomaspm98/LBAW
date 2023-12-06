<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Question;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function show($user_id): View
    {
        $this->authorize('show', Member::class);
        $member = Member::findOrFail($user_id);
        return view('pages.user', [
            'member' => $member,
        ]);
        
        
    }

    public function editShow($user_id): View|RedirectResponse
    {
        $memberBeingEdited = Member::findOrFail($user_id);
        $authenticatedMember = Auth::user();

        $this->authorize('edit', [$memberBeingEdited, $authenticatedMember]);

        return view('pages.edit_user', [
            'member' => $memberBeingEdited,
        ]);
    }


    
    public function update(Request $request, $user_id)
    {
        $memberBeingEdited = Member::findOrFail($user_id);
        $authenticatedMember = Auth::user();

        $this->authorize('edit', [$memberBeingEdited, $authenticatedMember]);

        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255|unique:member,username,' . $user_id . ',user_id',
            'user_email' => 'nullable|email|unique:member,user_email,' . $user_id . ',user_email',
            'user_password' => 'nullable|string|max:255|confirmed',
            'user_birthdate' => 'nullable|date|before_or_equal:' . now()->subYears(12)->format('Y-m-d'), Carbon::parse($request->user_birthdate)->toDateTimeString()
        ],

        [ // Custom error messages
            'username.string' => 'The username must be a string.',
            'username.max' => 'The username must not exceed 255 characters.',
            'username.unique' => 'The username is already taken.',
            
            'user_email.string' => 'The email must be a string.',
            'user_email.email' => 'The email must be a valid email address.',
            'user_email.max' => 'The email must not exceed 255 characters.',
            'user_email.unique' => 'The email is already taken.',
            
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            
            'picture.image' => 'The uploaded file must be an image.',
            'picture.mimes' => 'Only PNG, JPEG, and SVG formats are allowed.',
            'picture.max' => 'The file size must not exceed 10 megabytes.',

            'user_birthdate.date' => 'The birthdate must be a valid date.',
            'user_birthdate.before_or_equal' => 'The birthdate must be at least 12 years ago.',
        ]);


        $member = Member::findOrFail($user_id);
        $check = Auth::user();        
        $attributes = array_filter($request->all());
        
        $member->update($attributes);

        return redirect()->route('member.show', ['user_id' => $user_id])->with('success', 'User updated successfully');
    }



    public function delete($user_id)
    {
        $member = Member::findOrFail($user_id);
        $check = Auth::user();

        $this->authorize('delete', [$member, $check]);

        $member->delete();
       

        return redirect()->route('home')->with('success', 'User deleted successfully');
    }



}
