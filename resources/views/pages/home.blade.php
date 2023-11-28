@extends('layouts.app')

@section('content')


<div class="container">
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
    <div id="realTimeUpdates">
        <span>Total Questions: <b id="totalQuestions">{{ $totalQuestions }}</b></span>
        <span>Questions this week: <b id="questionsLastWeek">{{ $questionsLastWeek }}</b></span>
        <span>New Users this week: <b id="newUsersLastWeek">{{ $newUsersLastWeek }}</b></span>
    </div>

    @if (Auth::check())
    <a class="button" class="login-button" href="{{ url('/questions/create') }}"> Create a question </a> 
    @endif
    <h2>Top Questions</h2>
    <ul class="top_questions">
        @foreach ($questions as $question)
         @if($question->content_is_visible)
            <li class="question_card">

                <div class="question_user_container">
                    <a href="{{ route('member.show', $question->author) }}">
                        <div class="question_user_photo">
                            <img src="{{ Storage::url($question->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                        </div>
                    </a>
                    <a href="{{ route('member.show', $question->author) }}">
                        <p><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                    </a>
                </div>
                
                <div class="top_questions_tittle">
                    <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('questions.show', $question->question_id) }}"> <h3>{{ $question->question_title }}</h3></a>     
                    <a class="more_details" href="{{ route('questions.show', $question->question_id) }}"> more details</a>           
                </div>
                <div class="top_questions_n_answers">
                    @if($question->answer_count !== 1)
                    <p>{{$question->answer_count}} answers</p>
                    @else
                    <p>{{$question->answer_count}} answer</p>
                    @endif
                </div>

                <div class="top_questions_votes">
                    <p>{{$question->vote_count}} votes</p> 
                </div>
            </li>
            @endif
        @endforeach
    </ul>

</div>
@endsection