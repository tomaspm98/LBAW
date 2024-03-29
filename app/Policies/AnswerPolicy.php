<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Moderator;

use Illuminate\Support\Facades\Auth;

class AnswerPolicy
{
    /**
     * Determine if a given card can be shown to a user.
     */
    public function create(Member $member, $question_id): bool
    {
        $question = Question::findOrFail($question_id);
        return $member->user_id !== $question->content_author;
    }

    /**
     * Determine if all cards can be listed by a user.
     */
    public function edit(Member $member, Answer $answer): bool
    {
        return $member->user_id === $answer->content_author;
    }

    public function delete(Member $member, Answer $answer): bool
    {
        if ($member->user_id === $answer->content_author) {
            return true;
        }
        else if (Moderator::where('user_id', $member->user_id)->exists()){
            return true;
        }
        else {
            return false;
        }
    }

    public function mark_answer_correct(Member $member, $question_id): bool
    {
        
        return true;
    }

}
