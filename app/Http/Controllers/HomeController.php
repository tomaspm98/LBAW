<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::where('content_is_visible', true)->get();

        if (Auth::check()){
            $user_id = Auth::user()->user_id;
            $member = Member::findOrFail($user_id);
            $questions_followed = $member->follows()->get();
            $questions_followed = $questions_followed->pluck('question_id')->toArray();
            for ($i = 0; $i < count($questions_followed); $i++) {
                $questions_followed[$i] = Question::findOrFail($questions_followed[$i]);
            }
        } else {
            $questions_followed = [];
        }

        $totalQuestions = Question::count();

        $oneWeekAgo = Carbon::now()->subWeek()->toDateTimeString();
        $questionsLastWeek = Question::where('content_creation_date', '>=', $oneWeekAgo)->count();
        $newUsersLastWeek = Member::where('user_creation_date', '>=', $oneWeekAgo)->count();

        //$questions = Question::all();
        
        return view('pages.home', compact('questions', 'questions_followed', 'totalQuestions', 'questionsLastWeek', 'newUsersLastWeek'));
    }





}
