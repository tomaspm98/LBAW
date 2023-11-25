<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Moderator;
use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;



class ReportController extends Controller{

    public function showAllReports(){
        $this->authorize('showAll', Report::class);

        $reports = Report::all();
        return view('pages.reports', ['reports' => $reports]);
    }

    public function viewReport($reportId){
        $report = Report::where('report_id', $reportId)->first();
        $this->authorize('show', Report::class);
        $question = $report->question;
        $answer = $report->answer;
        $user = $report->user;
        $tag = $report->tag;
        return view('pages.report', ['report' => $report, 'question' => $question, 'answer' => $answer, 'user' => $user, 'tag' => $tag]);
    }

    public function createShow()
    {
        $tags = Tag::all();
        return view('pages.create_question', [
            'tags' => $tags
        ]);
    }

    public function createReportQuestion(Request $request, $question_id) {
        // Process the submitted report data

        $validatedData = $request->validate([
            'report_reason' => 'required|string',
            'report_text' => 'nullable|string',
        ]);

        $validatedData['content_reported_question'] = $question_id;
        $validatedData['report_creator'] = Auth::user()->user_id;
        $this->authorize('create', Report::class);
        $report = Report::create($validatedData);
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Report created successfully');
    }

    public function createReportAnswer(Request $request, $answer_id) {
        // Process the submitted report data

        $validatedData = $request->validate([
            'report_reason' => 'required|string',
            'report_text' => 'nullable|string',
        ]);


        $validatedData['content_reported_answer'] = $answer_id;
        $validatedData['report_creator'] = Auth::user()->user_id;
        $this->authorize('create', Report::class);
        $report = Report::create($validatedData);
        $answer = Answer::find($report->content_reported_answer);

        return redirect()->route('questions.show', ['question_id' => $answer->question->question_id])->with('success', 'Report created successfully');
    }




}




?>
