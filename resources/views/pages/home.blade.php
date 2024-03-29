@extends('layouts.app')

@section('content')


<div class="container">

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
    @if(session('success'))
        <div id="success-alert-login" class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-md-between mt-1">
        <p class="me-3 mb-2 mb-md-0">Total Questions: <b id="totalQuestions">{{ $totalQuestions }}</b></p>
        <p class="me-3 mb-2 mb-md-0">Questions this week: <b id="questionsLastWeek">{{ $questionsLastWeek }}</b></p>
        <p class="me-3 mb-2 mb-md-0">New Users this week: <b id="newUsersLastWeek">{{ $newUsersLastWeek }}</b></p>
    </div>

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-md-between my-1 py-2 border-top">
        @if (Auth::check())
        <a class="btn btn-primary" href="{{ url('/questions/create') }}"> Create a question </a> 
        @endif
    </div>
    <div class="hover-container">
        <div class="header-container">
                <i class="fa-solid fa-circle-question" style="color: #0f4aa8;"></i>
                <span class="hover-text">Here you can see the Top Questions in this platform, and if you click on one of them, you will be redirected to the question page to access more details of the question.</span>
                <h2 class="me-md-3 mb-2 mb-md-0">Top Questions</h2>
        </div>
    </div>


    <ul class="top_questions">
    @foreach ($questions as $question)
    @if($question->content_is_visible)
        <div class="card p-2 question_card">
            <div class="row no-gutters">
                <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                    <a href="{{ route('member.show', $question->author) }}">
                        <div class="question_user_photo">
                            <img src="{{ $question->author->getProfileImage() }}" alt="Profile Photo">
                        </div>
                        <div class="mt-2">
                            <p class="mb-0"><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                        </div>
                    </a>
                </div>

                <div class="col-md-7">
                    <div class="card-body">
                        <div class="top_questions_title">
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" 
                            href="{{ route('questions.show', $question->question_id) }}"  > 
                                <h3>{{ Str::limit($question->question_title, 50) }}</h3>
                            </a>     
                        </div>
                        <p class="question-text">{{ Str::limit($question->content_text, 70) }}   
                            @if(strlen($question->content_text > 50))
                            <a class="more_details" href="{{ route('questions.show', $question->question_id) }}"> more details</a>  
                            @endif
                        </p>   
                    </div>
                </div>
                <div class="col-md-3 d-flex justify-content-around align-items-center text-center">
                    <div class="top_questions_n_answers">
                        @if($question->answer_count !== 1)
                        <p>{{ $question->answer_count }} answers</p>
                        @else
                        <p>{{ $question->answer_count }} answer</p>
                        @endif
                    </div>
                    <div class="top_questions_votes">
                        <p>{{ $question->vote_count }} votes</p> 
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

    </ul>

    @if (Auth::check())
        @include('pages.personal_feed')
    @endif    
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successAlert = document.getElementById('success-alert-login');
        
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 3000); 
        }
    });
</script>
