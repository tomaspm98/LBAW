
<div class="container">   
    <div class="hover-container">
        <div class="header-container">
                <i class="fa-solid fa-circle-question" style="color: #0f4aa8;"></i>
                <span class="hover-text">Personal Feed presents you the questions you follow, so that you have more interest. It eases the way to access the more interesting questions according to each user.</span>
                <h2>Personal Feed</h2>
        </div>
    </div>
    <ul>
        @foreach ($questions_followed as $question)
         @if($question->content_is_visible)
            <li class="question_card">
                <div class="question_user_container">
                    <a href="{{ route('member.show', $question->author) }}"> 
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

</div>
