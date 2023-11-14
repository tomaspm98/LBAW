<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Question;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;



class UserController extends Controller
{
    public function show($user_id): View
    {

        $member = Member::findOrFail($user_id);

        return view('pages.user', [
            'member' => $member,
        ]);
    }

    public function editShow($user_id): View
    {
        $member = Member::findOrFail($user_id);

        return view('pages.edit_user', [
            'member' => $member,
        ]);
    }
    
    public function update(Request $request, $user_id)
    {
        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255|unique:member,username,' . $user_id . ',user_id',
            'user_email' => 'nullable|email|unique:member,user_email,' . $user_id . ',user_email',
            'user_password' => 'nullable|string|max:255|confirmed',
            'user_birthdate' => 'nullable|date',
        ]);

        $member = Member::findOrFail($user_id);        
        $attributes = array_filter($request->all());
        $member->update($attributes);

        return redirect()->route('member.show', ['user_id' => $user_id])->with('success', 'User updated successfully');
    }


    public function delete($user_id)
    {
        $member = Member::findOrFail($user_id);

        $member->delete();
        
        // To change, there is no home yet
        // I will leave it like this for now
        return redirect()->route('home')->with('success', 'User deleted successfully');
    }



}
