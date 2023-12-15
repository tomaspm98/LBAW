<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
use App\Models\Moderator;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;

class MemberPolicy
{
    /**
     * Determine if a given card can be shown to a user.
     */
    public function show(Member $member): bool
    {
        return Auth::check();
    }

    public function edit(Member $authenticatedMember, Member $memberBeingEdited): bool
{
    return $authenticatedMember->user_id === $memberBeingEdited->user_id;
}

    public function delete(Member $member): bool
    {    
        return Auth::user()->user_id === $member->user_id || Moderator::where('user_id', Auth::user()->user_id)->exists() || Admin::where('user_id', Auth::user()->user_id)->exists();
    }

    public function block(): bool
    {
        return Moderator::where('user_id', Auth::user()->user_id)->exists() || Admin::where('user_id', Auth::user()->user_id)->exists();
    }

    public function showBlocked(): bool
    {
        return Moderator::where('user_id', Auth::user()->user_id)->exists() || Admin::where('user_id', Auth::user()->user_id)->exists();
    }

}
