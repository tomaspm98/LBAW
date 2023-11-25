<?php 
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;
?>

<!-- In your Blade template -->
@extends('layouts.app')

@section('content')
    <div class="container report-container">
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
                <!-- Reported Answer -->
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
                <!-- Reported Comment -->
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
@endsection
