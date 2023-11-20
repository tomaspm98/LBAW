<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

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
    return $authenticatedMember->id === $memberBeingEdited->id;
}

    public function delete(Member $member): bool
    {
        return Auth::check()->user_id === $member->user_id;
    }

}
