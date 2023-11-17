<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Moderator;
use Illuminate\View\View;

class ModeratorController extends Controller
{

    public function showModeratorDetails($moderatorId)
    {
        $moderator = Moderator::find($moderatorId);

        if ($moderator) {
            $member = $moderator->member;

            if ($member) {
                $username = $member->username;
                $userEmail = $member->user_email;

                // Pass the data to a view or do something with it
                return view('moderator.details', [
                    'username' => $username,
                    'userEmail' => $userEmail,
                ]);
            } else {
                // Handle case where no member is associated with the moderator
            }
        } else {
            // Handle case where moderator with given user_id is not found
        }
    }


}