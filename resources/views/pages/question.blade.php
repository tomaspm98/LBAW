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



            <div class="comment_container w-100 bg-light mt-3 border-bottom rounded-2 p-1 d-flex position-relative" id="comment-{{$comment->comment_id}}">
                
                <form action="{{ route('votes.voteComment', ['question_id' => $question->question_id, 'answer_id' => $answer->answer_id, 'comment_id' => $comment -> comment_id]) }}#comment-{{$comment->comment_id}}" 
                method="POST" class="text-center p-2" style="width:70px"> 
                    @csrf
                    @php $userVote = $comment->userVote; @endphp
                    <button type="submit" name="upvote" value="up" class="btn p-2 rounded-top-5 {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">
                        <i class="bi bi-caret-up-fill"></i> <!--like-->
                    </button>
                    <p class="mt-3"><b>{{$comment->vote_count}}</b></p>
                    <button type="submit" name="upvote" value="down" class="btn p-2 rounded-bottom-5 {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">
                        <i class="bi bi-caret-down-fill"></i> <!--dislike-->
                    </button>
                </form>
            
                <div>
                    <div>
                        <span>
                            <b>Commented by: </b>
                            <a class="text-dark" href="">
                                {{$comment->author->username }}
                            </a>
                        </span>
                    </div>
    
                    <span class="mb-3" ><strong>Created at: </strong>{{\Carbon\Carbon::parse($comment->content_creation_date)->format('Y-m-d')}} </span>
    
                    <div class="content_text_container">
                        @if($comment->content_is_edited)
                        <span class="text-warning">edited</span>
                        @endif
                    </div>
    
                    <p>{{ $comment->content_text }}</p>

                </div>

                <div class="dropdown dropleft position-absolute top-0 end-0" >
                    <button class="btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">



                        
                        @if(Auth::check() && Auth::id()===$comment->content_author) <!-- restrict access only for owner -->
                        <li>
                            <form action="{{ route('comments.delete', [$question->question_id, $answer->answer_id, $comment->comment_id]) }} " 
                            method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button class="dropdown-item text-danger" type="submit">Delete</button>
                            </form>
                        </li>
                        <li>
                            <form method="GET" action="{{ route('comments.edit', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}" class="m-0">
                                @csrf
                                <button class="dropdown-item"> 
                                    Edit
                                </button>
                            </form>
                        </li>
                        
                        @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                        <li>
                            <form action="{{ route('comments.delete', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}" 
                            method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button class="dropdown-item text-danger" type="submit">Delete</button>
                            </form>
                        </li>
                        
                        @else






                        <div class="btn-group dropstart text-danger">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Report Comment
                            </button>
                            <ul class="dropdown-menu p-1" style="width:300px;">
                                        
                            <form class="p-2" id="reportCommentForm" method="POST" action="{{ route('report.comment', ['answer_id' =>$comment->answer->answer_id, 'comment_id' => $comment->comment_id]) }}">
                                <div class="form-group mb-1">
                                    @csrf
                                    <select class="form-select" name="report_reason" id="report_reason_comment" required>
                                    <option value="" disabled selected>Select reason</option>
                                    <option value="spam">Spam</option>
                                    <option value="offensive">Offensive</option>
                                    <option value="Rules Violation">Rules Violation</option>
                                    <option value="Inappropriate tag">Inappropriate tag</option>
                                    </select>
                                </div>
                                <div class="form-group my-2">
                                    <textarea class="form-control" name="report_text" placeholder="Additional text (optional)" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Report</button>
                            </form>








                            </ul>
                        </div>  









        
                        @endif    

                    </ul>
                </div>

            </div>

            @endif
            @endforeach
            <br>
            <hr>
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

document.addEventListener("DOMContentLoaded", function() {
    const reportButton = document.getElementById('showReportForm');
    const reportForm = document.getElementById('reportForm');

    reportButton.addEventListener('click', function() {
        reportButton.style.display = 'none'; 
        reportForm.style.display = 'block'; 
    });

    
});

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