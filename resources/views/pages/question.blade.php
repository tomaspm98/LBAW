<?php 
use App\Models\Moderator;
use App\Models\UserFollowQuestion;
?>
@extends('layouts.app')

@section('content')
<div id="success-message" style="display: none">
    Report submitted successfully!
</div>

<div id="correct-answer" class="popup-message" style="display: none">
    Answer marked as correct!
</div>

@if ($question->question_closed)
<div class="question-closed">
    <p><strong>Question Closed</strong></p>
</div>
@endif

@if ($question->content_is_visible)
@if (session('error'))
<div id="errorPopup" class="popup-message">
    {{ session('error') }}
</div>
@if (session('error'))
    <div id="errorPopup" class="popup-message">
        {{ session('error') }}
    </div>

    <script>
        let popup = document.getElementById('errorPopup');
        popup.style.display = 'block';

        setTimeout(function() {
            popup.style.display = 'none';
        }, 5000);
    </script>
@endif
<div class="container">
    
    <!--__________Question__________-->
    @include ('partials.question-info')
    

    <!--__________ANSWER FORM__________-->
    @if (Auth::check() && Auth::id()!=$question->content_author)
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
    @endif
</div>



<script>
    let popup = document.getElementById('errorPopup');
    popup.style.display = 'block';

    setTimeout(function() {
        popup.style.display = 'none';
    }, 5000);
</script>
@endif

<div class="container">
    
    <!--__________ QUESTION __________-->
    @include ('partials.question-info')

    <!--__________ ANSWER FORM __________-->
    @if (!$question->question_closed)
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
    @endif   

    @if($question->answer_count !== 1)
    <br><h3>{{ $question->answer_count }} Answers: </h3>
    @else
    <br><h3>{{ $question->answer_count }} Answer: </h3>
    @endif

    @foreach ($question->answers as $answer)

    <!--__________ ANSWER __________-->
    @if ($answer->content_is_visible)
    @include ('partials.answer-info')

    <!--__________ COMMENT FORM __________-->
    @if (!$question->question_closed)
    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComment{{$answer->answer_id}}" aria-expanded="false" aria-controls="collapseComment">
        <i class="bi bi-caret-down-fill">Comment</i>
    </button>
    <div class="border-top">
        <div class="collapse comment_form_container p-4" id="collapseComment{{$answer->answer_id}}">
            <form action="{{ route('comments.create', ['answer_id' => $answer->answer_id, 'question_id' => $question->question_id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea class="form-control fixed-height" style="height:70px" id="comment_content_text_{{ $answer->answer_id }}" name="content_text" required placeholder="Post Comment..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">submit</button>
            </form>
        </div>
    </div>
    @endif   

    @foreach ($answer->comments as $comment)
    <!--__________ COMMENT __________-->
    @if ($comment->content_is_visible)
    @include ('partials.comment-info')

    @endif
    @endforeach
    <hr>
    @endif
    @endforeach    
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
            editTagButton.style.display = 'none';
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






document.addEventListener("DOMContentLoaded", function() {
    const reportButton = document.getElementById('showReportCommentForm');
    const reportForm = document.getElementById('reportCommentForm');

    reportButton.addEventListener('click', function() {
        reportButton.style.display = 'none'; 
        reportForm.style.display = 'block'; 
    });
});


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

    function showSuccessMessage() {
    var notification = document.getElementById('correct-answer');
    notification.style.display = 'block';

    setTimeout(function() {
        notification.style.display = 'none';
    }, 3000); 
}    

document.addEventListener("DOMContentLoaded", function() {
    const followButton = document.getElementById('followQuestionButton');
    followButton.addEventListener('click', function() {
        const questionId = this.getAttribute('data-question-id');
        const url = `/questions/${questionId}/follow`; 
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ question_id: questionId })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.isFollowing)
            if (data.isFollowing) {
                followButton.textContent = 'Follow Question';
            } else {
                followButton.textContent = 'Unfollow Question';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            followButton.textContent = isCurrentlyFollowing ? 'Unfollow Question' : 'Follow Question';
            followButton.classList.toggle('btn-following', isCurrentlyFollowing);
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.vote-btn').forEach(button => {
    button.addEventListener('click', function() {
        console.log('clicked');
        const voteType = this.getAttribute('data-vote');
        console.log(voteType);

        const allButtons = document.querySelectorAll('.vote-btn');
        const isUnvoting = this.classList.contains('btn-success') && voteType === 'up' || 
                               this.classList.contains('btn-danger') && voteType === 'down';

                               if (isUnvoting) {
                allButtons.forEach(btn => {
                    btn.classList.remove('btn-success', 'btn-danger');
                    btn.classList.add('btn-primary');
                });
            } else {
            allButtons.forEach(btn => {
                btn.classList.remove('btn-success', 'btn-danger');
                btn.classList.add('btn-primary');
            });

            if (voteType === 'up') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
            } else if (voteType === 'down') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-danger');
            } else if (voteType === 'out') {
                this.classList.remove('btn-success', 'btn-danger');
                this.classList.add('btn-primary');
            }
        }

        const questionId = {{ $question->question_id }};
        const url = `/questions/${questionId}/votes`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json', 
            },
            body: JSON.stringify({ upvote: voteType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'You must be logged in to vote') {
                alert(data.message);  
                window.location.reload();  
            } else {
                console.log(data);
                document.querySelector('#voteCount').innerText = data.voteCount;
            }
        })
        .catch(error => console.error('Error:', error));
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.vote-btn-answer').forEach(button => {
    button.addEventListener('click', function() {
        console.log('clicked');
        const voteType = this.getAttribute('data-vote');
        const answerId = this.getAttribute('data-answer-id');
        const questionId = {{ $question->question_id }};
        const answerContainerId = 'answerContainer' + answerId;
        const allButtons = document.querySelectorAll(`#${answerContainerId} .vote-btn-answer`);
        const isUnvoting = this.classList.contains('btn-success') && voteType === 'up' || 
                               this.classList.contains('btn-danger') && voteType === 'down';
                               
                               if (isUnvoting) {
                allButtons.forEach(btn => {
                    btn.classList.remove('btn-success', 'btn-danger');
                    btn.classList.add('btn-primary');
                });
            } else {
            allButtons.forEach(btn => {
                btn.classList.remove('btn-success', 'btn-danger');
                btn.classList.add('btn-primary');
            });

            if (voteType === 'up') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
            } else if (voteType === 'down') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-danger');
            } else if (voteType === 'out') {
                this.classList.remove('btn-success', 'btn-danger');
                this.classList.add('btn-primary');
            }
        }

        const url = `/questions/${questionId}/answers/${answerId}/votes`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json', 
            },
            body: JSON.stringify({ upvote: voteType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'You must be logged in to vote') {
                alert(data.message);  
                window.location.reload();  
            } else {
                console.log(data);
                const commentVoteCountId = 'voteCountAnswer' + answerId;
                document.getElementById(commentVoteCountId).innerText = data.voteCount;
            }
        })
        .catch(error => console.error('Error:', error));
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.vote-btn-comment').forEach(button => {
    button.addEventListener('click', function() {
        console.log('clicked');
        event.preventDefault();
        const voteType = this.getAttribute('data-vote');
        console.log(voteType);
        const commentId = this.getAttribute('data-comment-id');
        const answerId = this.getAttribute('data-answer-id');
        const questionId = {{ $question->question_id }};
        const commentContainerId = 'commentContainer' + commentId;

        const allButtons = document.querySelectorAll(`#${commentContainerId} .vote-btn-comment`);
        const isUnvoting = this.classList.contains('btn-success') && voteType === 'up' || 
                               this.classList.contains('btn-danger') && voteType === 'down';

                               if (isUnvoting) {
                allButtons.forEach(btn => {
                    btn.classList.remove('btn-success', 'btn-danger');
                    btn.classList.add('btn-primary');
                });
            } else {
            allButtons.forEach(btn => {
                btn.classList.remove('btn-success', 'btn-danger');
                btn.classList.add('btn-primary');
            });

            if (voteType === 'up') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
            } else if (voteType === 'down') {
                this.classList.remove('btn-primary');
                this.classList.add('btn-danger');
            } else if (voteType === 'out') {
                this.classList.remove('btn-success', 'btn-danger');
                this.classList.add('btn-primary');
            }
        }

        const url = `/questions/${questionId}/answers/${answerId}/comments/${commentId}/votes`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json', 
            },
            body: JSON.stringify({ upvote: voteType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'You must be logged in to vote') {
                alert(data.message);  
                window.location.reload();  
            } else {
                console.log(data);
                const commentVoteCountId = 'voteCountComment' + commentId;
                document.getElementById(commentVoteCountId).innerText = data.voteCount;
            }
        })
        .catch(error => console.error('Error:', error));
        });
    });
});




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