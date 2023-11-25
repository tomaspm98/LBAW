<?php 
use App\Models\Moderator;
?>
@extends('layouts.app')

@section('content')
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
        <div class="content_container"> <!--Question-->
            <div class="content_top_container">

                <div class="content_left_container">
                    <a href=""> <!-- route('member.show', $question->author) -->
                        <div class="content_user_profile_photo">
                            <img src="{{ Storage::url($question->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                        </div>
                    </a>
                    <p><b>{{ $question->author->username }}</b></p>
                </div>
                
                <div class="question_tittle_container">
                    @if ($question->tag)
                    <p><strong>Tag:</strong> {{ $question->tag->tag_name }}</p>
                    @else
                    <p><strong>Tag:</strong> Not specified</p>
                    @endif
                    <h1>{{ $question->question_title }}</h1>
                    <p>
                        <strong>Created at: </strong>{{$question->content_creation_date}}
                    </p>
                </div>

                @if(Auth::check() && Auth::id()===$question->content_author) <!-- TODO: restrict access only for owner -->
                <div class="content_right_container"> 
                <form method="POST" action="{{ route('questions.delete', $question->question_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                </form>
                    <form method="GET" action="{{ route('questions.edit', $question->question_id) }}">
                        @csrf
                        @method('GET')
                        <button> 
                            Edit
                        </button>
                    </form>    
                </div>
                @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                <div class="content_right_container"> 
                    <form method="POST" action="{{ route('questions.delete', $question->question_id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                    </form>
                </div>
                @endif
            </div>

            <div class="content_bottom_container">

                <div class="content_left_container">    

                </div>
                
                <div class="content_text_container">
                    <h3>{{ $question->content_text }}</h3>
                    @if($question->content_is_edited)
                    <p>edited</p>
                    @endif
                </div>

                <div class="content_right_container"> 
                    <button> 
                        LIKE
                    </button>
                    <button> 
                        DISLIKE
                    </button>
                    <p><b>{{$question->vote_count}}</b></p>
                </div>
            </div>
        </div>

        <div>
            <form action="{{ route('answers.create', $question->question_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to submit this answer?')">
                @csrf
                <div class="form-group">
                    <label for="content_text">Post Answer:</label>
                    <textarea class="form-control" id="content_text" name="content_text" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Answer</button>
            </form>

        @if($question->answer_count !== 1)
        <br><h3>{{ $question->answer_count }} Answers: </h3>
        @else
        <br><h3>{{ $question->answer_count }} Answer: </h3>
        @endif
        @foreach ($question->answers as $answer)
        @if ($answer->content_is_visible)
        <div class="content_text_container">
                    @if($answer->content_is_edited)
                    <p>edited</p>
                    @endif
        </div>
        <div class="content_container">
            <div class="content_top_container">

                <div class="content_left_container">
                    <a href="">
                        <div class="content_user_profile_photo">
                            <img src="{{ Storage::url($answer->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
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

                @if(Auth::check() && Auth::id()===$answer->content_author) <!-- TODO: restrict access only for owner -->
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
                    @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                    <div class="content_right_container"> 
                        <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </div>   
                
                @endif    
                    <div>
                        <button class="button_like_dislike"> 
                            LIKE
                        </button>
                        <button class="button_like_dislike"> 
                            DISLIKE
                        </button>
                    </div>
                    <p><b>{{$answer->vote_count}}</b></p>
                </div>
            </div>
        </div>
        <div class="comment_form_container">
        <form action="{{ route('comments.create', ['answer_id' => $answer->answer_id, 'question_id' => $question->question_id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="comment_content_text_{{ $answer->answer_id }}">Post Comment:</label>
                    <textarea class="form-control" id="comment_content_text_{{ $answer->answer_id }}" name="content_text" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>    
        @foreach ($answer->comments as $comment)
        @if ($comment->content_is_visible)
        <div class="content_text_container">
                    @if($comment->content_is_edited)
                    <p>edited</p>
                    @endif
        </div>
        <div class="comment_container">
            <div class="content_top_container">
                <div class="content_left_container">
                    <a href="">
                      <div class="content_user_profile_photo">
                        <img src="{{ Storage::url($comment->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                        </div>
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

                @if(Auth::check() && Auth::id()===$comment->content_author) <!-- TODO: restrict access only for owner -->
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
                @endif    
                    <div>
                        <button class="button_like_dislike"> 
                            LIKE
                        </button>
                        <button class="button_like_dislike"> 
                            DISLIKE
                        </button>
                    </div>
                    <p><b>{{$comment->vote_count}}</b></p>
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
        });
    }
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