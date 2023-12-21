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



















        @if($question->answer_count !== 1)
        <br><h3>{{ $question->answer_count }} Answers: </h3>
        @else
        <br><h3>{{ $question->answer_count }} Answer: </h3>
        @endif
        @foreach ($question->answers as $answer)
        <div id="answerContainer{{ $answer->answer_id }}">
        @if ($answer->content_is_visible)
        <div class="content_text_container">
                    @if($answer->content_is_edited)
                    <p>edited</p>
                    @endif
        </div>
        <div class="content_container">
            <div class="content_top_container">

                <div class="content_left_container">
                    <a href="{{ route('member.show', $answer->author) }}">
                        <div class="content_user_profile_photo">
                        @php
                            $authorPicturePath = 'public/pictures/' . $answer->author->username . '/profile_picture.png';
                            $authorPicture = Storage::exists($authorPicturePath) ? asset('storage/pictures/' . $answer->author->username . '/profile_picture.png') : asset('storage/pictures/default/profile_picture.png');
                        @endphp
                        <img src="{{ $authorPicture }}" alt="Profile Photo">
                        </div>
                    </a>
                    <p><b>{{$answer->author->username }}</b></p>
                </div>
                
                <div class="content_text_container">
                    <p>
                        <h3>{{ $answer->content_text }}</h3>
                    </p>
                    <p>
                        <strong>Created at: </strong>{{$answer->content_creation_date}}
                    </p>
                </div>

                @if(Auth::check() && Auth::id()===$answer->content_author) 
                <div class="content_right_container"> 
                    <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                    <form method="GET" action="{{ route('answers.edit', [$question->question_id, $answer->answer_id]) }}">
                        @csrf
                        <button> 
                            Edit
                        </button>
                    </form> 
                    @elseif (Auth::check())
                    {{-- Verificar se a tag da pergunta é diferente da tag pela qual o moderator é responsavel --}}
                    {{-- @if (Moderator::where('user_id', Auth::user()->user_id)->exists() && $question->tag->tag_name !== Auth::user()->moderator->tag->tag_name) --}}
                    <div>
                        <button class="button_report" id="showReportAnswerForm"> 
                            Report
                        </button>
                        <form id="reportAnswerForm" method="POST" action="{{ route('report.answer', ['answer_id' => $answer->answer_id]) }}" style="display: none">
                            <div class="form-group"> 
                                @csrf
                                <select name="report_reason" id="report_reason_answer" required>
                                    <option value="" disabled selected>Select reason</option>
                                    <option value="spam">Spam</option>
                                    <option value="offensive">Offensive</option>
                                    <option value="Rules Violation">Rules Violation</option>
                                    <option value="Inappropriate tag">Inappropriate tag</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="report_text">Question Content</label>
                                <textarea name="report_text" placeholder="Additional text (optional)"></textarea>
                            </div>
                            <button type="submit" class="button_report_answer" onclick="showNotificationAnswer()">Submit Report</button>
                        </form>
                    </div> 
                    {{-- @endif  --}}
                    @endif

                    @if (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                    <div class="content_right_container"> 
                        <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </div> 
                    @endif 
                    
                    @if (Auth::check() && Auth::id() === $question->content_author) 
                    <div class="correct_answer">
                        <form action="{{ route('answers.correct', ['question_id' => $question->question_id, 'answer_id' => $answer -> answer_id]) }}" method="POST">
                            @csrf
                            @php $correct = $question->correct_answer; @endphp
                            <button type="submit" onclick="showSuccessMessage()" class="btn {{ $correct && $correct == $answer->answer_id ? 'btn-cor_answer' : 'btn-primary' }}">Mark as Correct</button>
                        </form>
                    </div>
                    @endif
      
                    <div>
                        <form id="voteForm" method="POST">
                            @csrf
                            @php $userVote = $answer->userVote; @endphp
                            <button type="button" data-vote="up" data-answer-id="{{$answer->answer_id}}" class="vote-btn-answer btn {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">Like</button>
                            <button type="button" data-vote="down" data-answer-id="{{$answer->answer_id}}" class="vote-btn-answer btn {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">Dislike</button>
                        </form>
                    </div>
                <p><b id="voteCountAnswer{{$answer->answer_id}}">{{$answer->vote_count}}</b></p>
                </div>
            </div>
        </div>
        </div>
        @if (!$question->question_closed)
        <div class="comment_form_container">
        <form action="{{ route('comments.create', ['answer_id' => $answer->answer_id, 'question_id' => $question->question_id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="comment_content_text_{{ $answer->answer_id }}">Post Comment:</label>
                    <textarea class="form-control" id="comment_content_text_{{ $answer->answer_id }}" placeholder="Write comment..." name="content_text" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div> 
        @endif   
        @foreach ($answer->comments as $comment)
        <div id="commentContainer{{ $comment->comment_id }}">
        @if ($comment->content_is_visible)
        <div class="content_text_container">
                    @if($comment->content_is_edited)
                    <p>edited</p>
                    @endif
        </div>
        <div class="comment_container">
            <div class="content_top_container">
                <div class="content_left_container">
                    <a href="{{ route('member.show', $comment->author) }}">
                      <div class="content_user_profile_photo">
                      @php
                            $authorPicturePath = 'public/pictures/' . $comment->author->username . '/profile_picture.png';
                            $authorPicture = Storage::exists($authorPicturePath) ? asset('storage/pictures/' . $comment->author->username . '/profile_picture.png') : asset('storage/pictures/default/profile_picture.png');
                      @endphp
                    <img src="{{ $authorPicture }}" alt="Profile Photo">                        </div>
                    </a>
                    <p><b>{{$comment->author->username }}</b></p>
                </div>
                
                <div class="content_text_container">
                    <p>
                        <h4>Comment : {{ $comment->content_text }}</h4>
                    </p>
                    <p>
                        <strong>Created at: </strong>{{$comment->content_creation_date}}
                    </p>
                </div>

                @if(Auth::check() && Auth::id()===$comment->content_author) 
                    <div class="content_right_container"> 
                        <form action="{{ route('comments.delete', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                        <form method="GET" action="{{ route('comments.edit', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}">
                            @csrf
                            <button> 
                                Edit
                            </button>
                    </form> 
                    </div>  
                    @elseif (Auth::check())
                    <div>
                        <button class="button_report" id="showReportCommentForm"> 
                            Report
                        </button>
                        <form id="reportCommentForm" method="POST" action="{{ route('report.comment', ['answer_id' =>$comment->answer->answer_id, 'comment_id' => $comment->comment_id]) }}" style="display: none">
                            <div class="form-group"> 
                                @csrf
                                <select name="report_reason" id="report_reason_comment" required>
                                    <option value="" disabled selected>Select reason</option>
                                    <option value="spam">Spam</option>
                                    <option value="offensive">Offensive</option>
                                    <option value="Rules Violation">Rules Violation</option>
                                    <option value="Inappropriate tag">Inappropriate tag</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="report_text">Question Content</label>
                                <textarea name="report_text" placeholder="Additional text (optional)"></textarea>
                            </div>
                            <button type="submit" class="button_report_answer" onclick="showNotificationComment()">Submit Report</button>
                        </form>
                    </div>
                    @endif

                   
                @if (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                    <form action="{{ route('comments.delete', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                    @endif  
                   
                    <div>
                        <form id="voteForm" method="POST">
                            @csrf
                            @php $userVote = $comment->userVote; @endphp
                            <button type="submit" data-vote="up" data-answer-id="{{$comment->answer_id}}" data-comment-id="{{$comment->comment_id}}" class="vote-btn-comment btn {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">Like</button>
                            <button type="submit" data-vote="down" data-answer-id="{{$comment->answer_id}}" data-comment-id="{{$comment->comment_id}}" class="vote-btn-comment btn {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">Dislike</button>
                        </form>
                    </div>
                    <p><b id ="voteCountComment{{ $comment->comment_id }}">{{$comment->vote_count}}</b></p>
                </div>
            </div>
        </div>
        </div>
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
        console.log(voteType);
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

