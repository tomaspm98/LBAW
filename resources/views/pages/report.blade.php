<?php 
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Member;
?>

@extends('layouts.app')

@section('content')

<div id="success-message" style="display: none">

    Report assigned successfully!
</div>

<div class="container report-container">
    @if(!$report->handler) 
        <form method="POST" action="{{ route('reports.assign', $report->report_id) }}">
            @csrf
            <button type="submit" class="btn btn-primary" onclick="showSuccess()">Assign to Me</button>
        </form> 
    @else
        @php $handler = Member::find($report->report_handler) @endphp
        <div class="report-handler">
            <h4>Report handler: {{ $handler->username }}</h4>
        </div>
    @endif
        <h2><strong>Report Creator: {{ $report->creator->username ?? 'unknown' }}</strong></h2>
        <div class="report-details">
            <br>
            <h3>Reason: {{ $report->report_reason }}</h3>
            <br>
            <h3>Text: {{ $report->report_text }}</h3>
            <br>
            <strong><h3>Reported content:</h3></strong>
            @if($report->content_reported_question)
                @php $question = Question::find($report->content_reported_question) @endphp
                <div class="reported-item">
                    <span>Question:</span>
                    <a href="{{ route('questions.show', $report->content_reported_question) }}">
                        <h3>{{ $question->question_title }}</h3>
                    </a>
                </div>
            @elseif ($report->content_reported_answer)
                @php $answer = Answer::find($report->content_reported_answer) @endphp
                <div class="reported-item">
                    <span>Answer:</span>
                    <p>{{ $answer->content_text }}</p>
                    <p>On Question:</p>
                    <a href="{{ route('questions.show', $answer->question->question_id) }}">
                        <h3>{{ $answer->question->question_title }}</h3>
                    </a>
                </div>
            @elseif ($report->content_reported_comment)
                @php $comment = Comment::find($report->content_reported_comment) @endphp
                <div class="reported-item">
                    <span>Comment:</span>
                    <p>{{ $comment->content_text }}</p>
                    <p>On Question:</p>
                    <a href="{{ route('questions.show', $comment->answer->question->question_id) }}">
                        <h3>{{ $comment->answer->question->question_title }}</h3>
                    </a>
                </div>
            @endif
        </div>
    </div>
<script>
function showSuccess(){
    var notification = document.getElementById('success-message');
        notification.style.display = 'block';

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000);
    
}

</script>


@endsection
