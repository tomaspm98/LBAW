<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Question;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;


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

        $rules = [];


        if ($request->has('username')) {
            $rules['username'] = 'string|max:255|unique:member,username,' . $user_id . ',user_id';
        }

        if ($request->has('user_email')) {
            $rules['user_email'] = 'email|unique:member,user_email,' . $user_id . ',user_email';
        }

        if ($request->has('user_password')) {
            $rules['user_password'] = 'string|max:255|confirmed';
        }


        $validatedData = $request->validate($rules);
        

        $member = Member::findOrFail($user_id);

        $member->update($validatedData);

        return redirect()->route('member.show', ['user_id' => $user_id])->with('success', 'Question updated successfully');
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
