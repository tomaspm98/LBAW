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
                    <p>
                        <strong>Tag:</strong> {{ $question->tag->tag_name }}
                    </p>
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
        <p>{{$comment->content_text}}</p>
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
