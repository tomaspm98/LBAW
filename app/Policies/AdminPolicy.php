<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class AdminPolicy
{
    /**
     * Determine if a given card can be shown to a user.
     */
    public function show(): bool
    {
        return Admin::where('user_id', Auth::user()->user_id)->exists();
    }

    public function edit(Member $authenticatedMember, Member $memberBeingEdited): bool
{
    return $authenticatedMember->id === $memberBeingEdited->id;
}

    public function delete(Member $member): bool
    {
        return Auth::user()->user_id === $member->user_id;
    }

    public function showTags(): bool
    {
        return Admin::where('user_id', Auth::user()->user_id)->exists();
    }

    public function create(): bool
    {
        return Admin::where('user_id', Auth::user()->user_id)->exists();
    }

}
