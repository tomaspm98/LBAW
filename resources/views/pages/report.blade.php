<?php 
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Member;
use App\Models\Admin;
use App\Models\Moderator;

$moderators = Moderator::all();

if($report->content_reported_question){
    $q1 = Question::find($report->content_reported_question);
    $person = $q1->content_author;
}
elseif ($report->content_reported_answer) {
    $q1 = Answer::find($report->content_reported_answer);
    $person = $q1->content_author;
}
else{
    $q1 = Comment::find($report->content_reported_comment);
    $person = $q1->content_author;
}

$reported_person = Member::find($person);



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
    @if ($report->handler)
        @php $handler = Member::find($report->report_handler) @endphp
        <div class="report-handler">
            <h4>Report handler: {{ $handler->username }}</h4>
        </div>
    @elseif(Admin::find(Auth::user()->user_id))
        <form method="POST" action="{{ route('reports.assign', ['report_id' => $report->report_id]) }}">
            @csrf
            <label for="moderator_select">Assign to:</label>
            <select name="moderator" id="moderator_select">
                @foreach($moderators as $moderator)
                    <option value="{{ $moderator->user_id }}">{{ $moderator->member->username }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
    @elseif(!$report->handler) 
        <form method="POST" action="{{ route('reports.assign', ['report_id' => $report->report_id]) }}">
            @csrf
            <button type="submit" class="btn btn-primary" onclick="showSuccess()">Assign to Me</button>
            <input type="hidden" name="assign_to_me" value="true">
        </form> 
    @endif
        <h2><strong>Report Creator: {{ $report->creator->username ?? 'unknown' }}</strong></h2>
        <div class="report-details">
            <br>
            <h3>Reason: {{ $report->report_reason }}</h3>
            <br>
            <h3>Text: {{ $report->report_text }}</h3>
            <br>
            <h3>Reported Person: </h3><a href="{{ route('member.show', $reported_person->user_id) }}"><h3>{{ $reported_person->username }}</h3></a>     
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
                <input type="radio" id="punished_yes" name="punished" value="yes">
                Punished
            </label><br>

            <div id="punishment_options" style="display: none;">
                <label for="block">
                    <input type="radio" id="block" name="punishment" value="block">
                    Block
                </label><br>

                <label for="delete">
                    <input type="radio" id="delete" name="punishment" value="delete">
                    Delete
                </label><br>
            </div>
            
            <label for="punished_no">
                <input type="radio" id="punished_no" name="punished" value="no">
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

function showPunishmentOptions() {
            var punishmentOptions = document.getElementById('punishment_options');
            punishmentOptions.style.display = 'block';
        }

function validateForm() {
            var punishedYes = document.getElementById('punished_yes').checked;
            var punishmentBlock = document.getElementById('block').checked;
            var punishmentDelete = document.getElementById('delete').checked;
            var comment = document.getElementById('comment').value.trim();

            if (!punishedYes) {
                alert("Please select whether the user was punished.");
                return false;
            }

            if (!(punishmentBlock || punishmentDelete)) {
                alert("Please select the type of punishment: Block or Delete.");
                return false;
            }

            if (comment === '') {
                alert("Please provide a brief comment.");
                return false;
            }

            displaySuccessMessageClose();
            setTimeout(function () {
                hideSuccessMessageClose();
            }, 3000);

            return true;
        }
        
        // Function to show punishment options when "Punished" is selected
        document.getElementById('punished_yes').addEventListener('click', showPunishmentOptions);




</script>


@endsection