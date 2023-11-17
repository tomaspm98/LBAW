<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Moderator;
use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function showAllUsers()
    {
        $check = Auth::user();
        if ($check != null){
            $isAdmin = Admin::where('user_id', $check->user_id)->exists();
            if(!$isAdmin){
                return redirect()->route('home');
            }
        }
        else if ($check == null){
            return redirect()->route('login');
        }

        $moderatorIds = Moderator::pluck('user_id')->toArray();
        $adminIds = Admin::pluck('user_id')->toArray();
        $regularMembers = Member::where('user_id', '!=', -1)
        ->whereNotIn('user_id', array_merge($moderatorIds, $adminIds))
        ->get();
                $tags = Tag::all();

        return view('pages.admin_assign', ['users' => $regularMembers, 'tags' => $tags]);
    }

    public function showAllModerators()
    {
        $check = Auth::user();
        if ($check != null){
            $isAdmin = Admin::where('user_id', $check->user_id)->exists();
            if(!$isAdmin){
                return redirect()->route('home');
            }
        }
        else if ($check == null){
            return redirect()->route('login');
        }
        $moderatorIds = Moderator::pluck('user_id');
        $moderators = Member::whereIn('user_id', $moderatorIds)->get();
        return view('pages.admin_remove', ['moderators' => $moderators]);
    }

    public function addModerator(Request $request, $user_id)
    {
        $check = Auth::user();
        if ($check != null){
            $isAdmin = Admin::where('user_id', $check->user_id)->exists();
            if(!$isAdmin){
                return redirect()->route('home');
            }
        }
        else if ($check == null){
            return redirect()->route('login');
        }
        $tagId = $request->input('tag_id');
    
        // Check if the user is already a moderator
        if (!Moderator::where('user_id', $user_id)->exists()) {
            Moderator::create([
                'user_id' => $user_id,
                'tag_id' => $tagId, // Assign the selected tag ID to the moderator
            ]);
            return redirect()->route('admin.users')->with('success', 'User added as a moderator.');
        }
        return "User with ID: $user_id is already a moderator.";
    }
    

    public function removeModerator($userId)
    {
        $check = Auth::user();
        if ($check != null){
            $isAdmin = Admin::where('user_id', $check->user_id)->exists();
            if(!$isAdmin){
                return redirect()->route('home');
            }
        }
        else if ($check == null){
            return redirect()->route('login');
        }
    $moderator = Moderator::where('user_id', $userId)->first();
    if ($moderator) {
        $moderator->delete();
        return redirect()->route('admin.moderators')->with('success', 'User added as a moderator.');
    }
    return "User with ID: $userId is not a moderator.";
    }

}