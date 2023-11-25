<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Vote;
use App\Models\Moderator;

use Illuminate\Support\Facades\Auth;

class VotePolicy
{
    public function create(Member $member): bool
    {
        return Auth::check();
    }


}
