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

<div id="close-success-message" style="display: none">

    Report closed successfully!
</div>

<div class="container report-container">
    @if(!$report->handler) 
    <form method="POST" action="{{ route('reports.assign', ['report_id' => $report->report_id]) }}">
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

    @if ($report->report_dealt == 1 )
        <div class="report-close-info">
            <h3>Report dealt with</h3>
            <h4> <strong>Punished:</strong> {{ $report->report_accepted == 1 ? 'YES' : 'NO' }}</h4>
            <h4><strong>Comment: </strong>{{ $report->report_answer }}</h4>
            
        </div>


    @elseif ($report->report_handler == Auth::user()->user_id)
    <div class="moderator-dealing">
        <form method="POST" action="{{ route('report.close', ['report_id' => $report->report_id]) }}" onsubmit="return validateForm()">
            @csrf
            @method('POST') 
        
            <label for="punished_yes">
                <input type="radio" id="punished_yes" name="punished" value="yes" required>
                Punished
            </label><br>
        
            <label for="punished_no">
                <input type="radio" id="punished_no" name="punished" value="no" required>
                Not Punished
            </label><br>
        
            <label for="comment">Brief Comment:</label><br>
            <textarea id="comment" name="comment" rows="4" cols="50" required placeholder="Introduce a brief comment on the report"></textarea><br>
        
            <input type="submit" value="Submit">
        </form>



    </div>
    @endif


<script>
function showSuccess(){
    var notification = document.getElementById('success-message');
        notification.style.display = 'block';

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000);
    
}

function validateForm() {
    var punishedYes = document.getElementById('punished_yes').checked;
    var punishedNo = document.getElementById('punished_no').checked;
    var comment = document.getElementById('comment').value.trim();

    if (!(punishedYes || punishedNo)) {
        alert("Please select whether the user was punished or not.");
        return false;
    }

    if (comment === '') {
        alert("Please provide a brief comment.");
        return false;
    }

    displaySuccessMessageClose();
    setTimeout(function() {
        hideSuccessMessageClose();
    }, 3000);


    return true;
}




</script>


@endsection
