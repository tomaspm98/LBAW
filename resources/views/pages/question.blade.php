<?php 
use App\Models\Moderator;
?>
@extends('layouts.app')

@section('content')

<div id="success-message" style="display: none">
    Report submitted successfully!
</div>


@if ($question->content_is_visible)
    @if (session('error'))
        <div id="errorPopup" class="popup-message">
            {{ session('error') }}
        </div>

        <script>
            // Show the popup
            let popup = document.getElementById('errorPopup');
            popup.style.display = 'block';

            // Hide the popup after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                popup.style.display = 'none';
            }, 5000);
        </script>
    @endif

    <div class="container">

    @include ('partials.question-info')
        
        <!--ANSWER FORM-->
        <div>
            <div class="container mt-4 p-4">
                <div class="card-body">
                    <form action="{{ route('answers.create', $question->question_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to submit this answer?')">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control fixed-height" id="content_text" name="content_text" rows="8" required placeholder="Post Answer..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Post Answer</button>
                    </form>
                </div>
            </div>

            @if($question->answer_count !== 1)
            <br><h3>{{ $question->answer_count }} Answers: </h3>
            @else
            <br><h3>{{ $question->answer_count }} Answer: </h3>
            @endif


            @foreach ($question->answers as $answer)
            @if ($answer->content_is_visible)

            @include ('partials.answer-info')       
   
            <!--COMMENTS FORM-->
            <div class="comment_form_container p-4">
                <form action="{{ route('comments.create', ['answer_id' => $answer->answer_id, 'question_id' => $question->question_id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control fixed-height" style="height:70px" id="comment_content_text_{{ $answer->answer_id }}" name="content_text" required placeholder="Post Comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">submit</button>
                </form>
            </div>  

            @foreach ($answer->comments as $comment)
            @if ($comment->content_is_visible)
            
            @include ('partials.comment-info')
            
            @endif
            @endforeach
            <br>
            <hr >
            <br>
            @endif
            @endforeach    
        </div>
    </div>
    @else
     <?php abort(404); ?>
  @endif    
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    const editTagButton = document.getElementById('editTagButton');
    const tagEditSection = document.getElementById('tagEditSection');
    if (editTagButton && tagEditSection) {
        editTagButton.addEventListener('click', function() {
            tagEditSection.style.display = 'block';
        });
    }
});

// document.addEventListener("DOMContentLoaded", function() {
//     const reportButton = document.getElementById('showReportForm');
//     const reportForm = document.getElementById('reportForm');

//     reportButton.addEventListener('click', function() {
//         reportButton.style.display = 'none'; 
//         reportForm.style.display = 'block'; 
//     });

    
// });

function showNotification() {
        var reason = document.getElementById("report_reason");
        reason = reason.value;
        console.log(reason);
        if (reason === ""){
            console.log("null");
            return false;
        }
        var notification = document.getElementById('success-message');
        notification.style.display = 'block';

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000); 
    }









document.addEventListener("DOMContentLoaded", function() {
    const reportButton = document.getElementById('showReportAnswerForm');
    const reportForm = document.getElementById('reportAnswerForm');

    reportButton.addEventListener('click', function() {
        reportButton.style.display = 'none'; 
        reportForm.style.display = 'block';
    });
});


function showNotificationAnswer() {
        var reason = document.getElementById('report_reason_answer');
        reason = reason.value;
        console.log(reason);
        if (reason === ""){
            console.log("null");
            return false;
        }
        var notification = document.getElementById('success-message');
        notification.style.display = 'block';

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000); 
    }






// document.addEventListener("DOMContentLoaded", function() {
//     const reportButton = document.getElementById('showReportCommentForm');
//     const reportForm = document.getElementById('reportCommentForm');

//     reportButton.addEventListener('click', function() {
//         reportButton.style.display = 'none'; 
//         reportForm.style.display = 'block'; 
//     });
// });


function showNotificationComment() {
        var reason = document.getElementById('report_reason_comment');
        reason = reason.value;
        console.log(reason);
        if (reason === ""){
            console.log("null");
            return false;
        }
        var notification = document.getElementById('success-message');
        notification.style.display = 'block';

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000);
    }   


</script>



<!--

    TODO:
    1. [ ] Restrict featuring post answer (only for members)
    2. [ ] Restrict delete featuring (only for owner (and admin?))
    3. [ ] Restrict edit featuring (only for owner (and admin?))    
    4. [ ] Edit (question, answer, comment) featuring
    5. [ ] Delete question featuring dosent work correctly
    6. [ ] Fix creation_date format
    7. [ ] In some content when clic con a profile photo or username redirect to the owner user profile 

    not high priority:
    4. [ ] load profiles photos 
    5. [ ] votes (not high priority)
    6. [ ] report featuring
    7. [ ] add icones for buttons

-->