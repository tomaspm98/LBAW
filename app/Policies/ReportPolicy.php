<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Admin;
use App\Models\Moderator;
use Illuminate\Support\Facades\Log;
use App\Models\Report;


use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    /**
     * Determine if a given card can be shown to a user.
     */
    public function showAll(): bool
    {
        return Moderator::where('user_id', Auth::user()->user_id)->exists() || Admin::where('user_id', Auth::user()->user_id)->exists();
    }

    public function show(): bool
    {
        return Moderator::where('user_id', Auth::user()->user_id)->exists() || Admin::where('user_id', Auth::user()->user_id)->exists();
    }

}
