<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Comment;
use App\Models\Moderator;

use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    /**
     * Determine if a given card can be shown to a user.
     */
    public function create(Member $member): bool
    {
        return Auth::check();
    }

    public function edit(Member $member, Comment $comment): bool
    {
        return $member->user_id === $comment->content_author;
    }

    public function delete(Member $member, Comment $comment): bool
    {
        if ($member->user_id === $comment->content_author) {
            return true;
        }
        else if (Moderator::where('user_id', $member->user_id)->exists()){
            return true;
        }
        else {
            return false;
        }
    }

}
