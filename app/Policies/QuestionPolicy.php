<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;

use Illuminate\Support\Facades\Auth;

class QuestionPolicy
{
    /**
     * Determine if a given card can be shown to a user.
     */
    public function create(Member $member): bool
    {
        return Auth::check();
    }

    /**
     * Determine if all cards can be listed by a user.
     */
    public function edit(Member $member, Question $question): bool
    {
        return $member->user_id === $question->content_author;
    }

    public function delete(Member $member, Question $question): bool
    {
        return $member->user_id === $question->content_author;
    }

}
