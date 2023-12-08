<?php
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Moderator;
use App\Models\Admin;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

if (Moderator::find(Auth::user()->user_id)){
    $moderator = Moderator::find(Auth::user()->user_id);
} else {
    $moderator = Admin::find(Auth::user()->user_id);
}
?>

@extends('layouts.app')

@section('content')
<div class="reports-header">
    <h1 class="header-title">All Closed Reports</h1>
</div>

@if($reports->isEmpty())
    <p class="no-reports">No reports found.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Creator</th>
                <th>Reason</th>
                <th>Text</th>
                <th>Tag</th>
                <th>Select</th>
                <th>Assigned</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    @if (Admin::find(Auth::user()->user_id))
                        @if ($report->report_dealt)
                            @if($report->content_reported_question)
                                @php $question = Question::find($report->content_reported_question) @endphp
                                <td>{{ $report->creator->username ?? 'unknown' }}</td>
                                <td>{{ $report->report_reason }}</td>
                                <td>{{ $report->report_text }}</td>
                                <td>{{ $question->tag->tag_name }}</td>
                                <td><a href="{{ route('report.view', $report->report_id) }}">Go to this report</a></td>
                                
                                @if (!$report->report_handler)
                                    <td>NO</td>
                                @else
                                    <?php $moderator = Member::find($report->report_handler) ?>
                                    <td>{{$moderator->username}}</td>
                                @endif
                            @elseif ($report->content_reported_answer)
                                @php $answer = Answer::find($report->content_reported_answer) @endphp
                                <td>{{ $report->creator->username ?? 'unknown' }}</td>
                                <td>{{ $report->report_reason }}</td>
                                <td>{{ $report->report_text }}</td>
                                <td>{{ $answer->question->tag->tag_name }}</td>
                                <td><a href="{{ route('report.view', $report->report_id) }}">Go to this report</a></td>
                                
                                @if (!$report->report_handler)
                                    <td>NO</td>
                                @else
                                    <?php $moderator = Member::find($report->report_handler) ?>
                                    <td>{{$moderator->username}}</td>
                                @endif
                            @elseif ($report->content_reported_comment)
                                @php $comment = Comment::find($report->content_reported_comment) @endphp
                                <td>{{ $report->creator->username ?? 'unknown' }}</td>
                                <td>{{ $report->report_reason }}</td>
                                <td>{{ $report->report_text }}</td>
                                <td>{{ $comment->answer->question->tag->tag_name }}</td>
                                <td><a href="{{ route('report.view', $report->report_id) }}">Go to this report</a></td>
                                
                                @if (!$report->report_handler)
                                    <td>NO</td>
                                @else
                                    <?php $moderator = Member::find($report->report_handler) ?>
                                    <td>{{$moderator->username}}</td>
                                @endif
                            @endif
                        @endif
                    @else 
                        @if (!$report->report_dealt)
                            @if($report->content_reported_question)
                                @php $question = Question::find($report->content_reported_question) @endphp
                                @if ($moderator->tag_id === $question->tag->tag_id)
                                    <td>{{ $report->creator->username ?? 'unknown' }}</td>
                                    <td>{{ $report->report_reason }}</td>
                                    <td>{{ $report->report_text }}</td>
                                    <td>{{ $question->tag->tag_name }}</td>
                                    <td><a href="{{ route('report.view', $report->report_id) }}">Go to this report</a></td>
                                    @if( $moderator->user_id === $report->report_handler)
                                        <td>TO ME</td>
                                    @elseif (!$report->report_handler)
                                        <td>NO</td>
                                    @else
                                        <td>TO OTHER</td>
                                    @endif
                                @endif
                            @elseif ($report->content_reported_answer)
                                @php $answer = Answer::find($report->content_reported_answer) @endphp
                                @if ($moderator->tag_id === $answer->question->tag->tag_id)
                                    <td>{{ $report->creator->username ?? 'unknown' }}</td>
                                    <td>{{ $report->report_reason }}</td>
                                    <td>{{ $report->report_text }}</td>
                                    <td>{{ $answer->question->tag->tag_name }}</td>
                                    <td><a href="{{ route('report.view', $report->report_id) }}">Go to this report</a></td>
                                    @if( $moderator->user_id === $report->report_handler)
                                        <td>TO ME</td>
                                    @elseif (!$report->report_handler)
                                        <td>NO</td>
                                    @else
                                        <td>TO OTHER</td>
                                    @endif
                                @endif
                            @elseif ($report->content_reported_comment)
                                @php $comment = Comment::find($report->content_reported_comment) @endphp
                                @if ($moderator->tag_id === $comment->answer->question->tag->tag_id)
                                    <td>{{ $report->creator->username ?? 'unknown' }}</td>
                                    <td>{{ $report->report_reason }}</td>
                                    <td>{{ $report->report_text }}</td>
                                    <td>{{ $comment->answer->question->tag->tag_name }}</td>
                                    <td><a href="{{ route('report.view', $report->report_id) }}">Go to this report</a></td>
                                    @if( $moderator->user_id === $report->report_handler)
                                        <td>TO ME</td>
                                    @elseif (!$report->report_handler)
                                        <td>NO</td>
                                    @else
                                        <td>TO OTHER</td>
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif

                    
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
