<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Member;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::where('content_is_visible', true)->get();

        $totalQuestions = Question::count();

        $oneWeekAgo = Carbon::now()->subWeek()->toDateTimeString();
        $questionsLastWeek = Question::where('content_creation_date', '>=', $oneWeekAgo)->count();
        $newUsersLastWeek = Member::where('user_creation_date', '>=', $oneWeekAgo)->count();

        //$questions = Question::all();
        
        return view('pages.home', compact('questions', 'totalQuestions', 'questionsLastWeek', 'newUsersLastWeek'));
    }





    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
