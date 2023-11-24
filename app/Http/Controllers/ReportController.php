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







}




?>
