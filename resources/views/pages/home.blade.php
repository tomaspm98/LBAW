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
        <p>Total Questions: <span id="totalQuestions">{{ $totalQuestions }}</span></p>
        <p>Questions this week: <span id="questionsLastWeek">{{ $questionsLastWeek }}</span></p>
        <p>New Users this week: <span id="newUsersLastWeek">{{ $newUsersLastWeek }}</span></p>
    </div>

    @if (Auth::check())
    <a class="button" class="login-button" href="{{ url('/questions/create') }}"  style="float:right;"> Create a question </a> 
    @endif
    <div class="hover-container">
        <h2>Top Questions</h2>
        <span class="hover-text">Here you can see the Top Questions in this platform, and if you click on one of them, you will be redirected to the question page to access to more details of the question.</span>
    </div>
    <ul>
        @foreach ($questions as $question)
         @if($question->content_is_visible)
            <li class="question_card">

                <div class="question_user_container">
                    <a href="{{ route('member.show', $question->author) }}"> <!-- route('member.show', $question->author) -->
                        <div class="question_user_photo">
                            <img src="{{ asset($question->author->picture) ?? asset('pictures/default/profile_picture.png') }}" alt="Profile Photo">
                        </div>
                    </a>
                    <p><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                </div>
                
                <div class="top_questions_tittle">
                    <a href="{{ route('questions.show', $question->question_id) }}"> <h3>{{ $question->question_title }}</h3></a>     
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
    @if (Auth::check())
        @include('pages.personal_feed')
    @endif    
</div>
@endsection