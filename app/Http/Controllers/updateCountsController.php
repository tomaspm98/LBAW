<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Question;
use Carbon\Carbon;

class updateCountsController extends Controller
{
    public function updateQuestionCount()
    {
        $totalQuestions = Question::count();

        $oneWeekAgo = Carbon::now()->subWeek()->toDateTimeString();

        $questionsLastWeek = Question::where('content_creation_date', '>=', $oneWeekAgo)->count();
        $newUsersLastWeek = Member::where('user_creation_date', '>=', $oneWeekAgo)->count();

        // Do not broadcast the event, just return the JSON response
        return response()->json([
            'totalQuestions' => $totalQuestions,
            'questionsLastWeek' => $questionsLastWeek,
            'newUsersLastWeek' => $newUsersLastWeek,
            'message' => 'Question count updated successfully'
        ]);
    }
}
