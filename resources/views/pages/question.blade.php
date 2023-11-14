@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="content_container"> <!--Question-->
            <div class="content_top_container">

                <div class="content_left_container">
                    <div class="content_user_profile_photo">
                        <img src="" alt="Profile Photo">
                    </div>
                    <p><b>{{ $question->author->username }}</b></p>
                </div>
                
                <div class="question_tittle_container">
                    @if ($question->tag)
                    <strong>Tag:</strong> {{ $question->tag->tag_name }}
                    @else
                    <strong>Tag:</strong> Not specified
                    @endif
                    <h1>{{ $question->question_title }}</h1>
                    <p>
                        <strong>Created at: </strong>{{$question->content_creation_date}}
                    </p>
                </div>

                @if(true) <!-- TODO: restrict access only for owner -->
                <div class="content_right_container"> 
                    <button> 
                        delete
                    </button>
                    <button> 
                        Edit
                    </button>
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
                    <p><b>999</b></p>
                </div>
            </div>
        </div>

        =======
        {{-- Delete Button --}}
        <form method="POST" action="{{ route('questions.delete', $question->question_id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete Question</button>
        </form>
        >>>>>>> US201_202_31_32_33_34

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
        <div class="content_container">
            <div class="content_top_container">

                <div class="content_left_container">
                    <div class="content_user_profile_photo">
                        <img src="" alt="Profile Photo">
                    </div>
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

                @if(true) <!-- TODO: restrict access only for owner -->
                <div class="content_right_container"> 
                    <button> 
                        delete
                    </button>
                    <button> 
                        Edit
                    </button>
                    <button> 
                        LIKE
                    </button>
                    <button> 
                        DISLIKE
                    </button>
                    <p><b>88</b></p>
                </div>
                @endif
            </div>
        </div>

        @foreach ($answer->comments as $comment)
        <div class="comment_container">
            <div class="content_top_container">
                <div class="content_left_container">
                    <div class="content_user_profile_photo">
                        <img src="" alt="Profile Photo">
                    </div>
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

                @if(true) <!-- TODO: restrict access only for owner -->
                <div class="content_right_container"> 
                    <button> 
                        delete
                    </button>
                    <button> 
                        Edit
                    </button>
                    <button> 
                        LIKE
                    </button>
                    <button> 
                        DISLIKE
                    </button>
                    <p><b>77</b></p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        <hr>
        @endforeach    
    </div>
@endsection

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