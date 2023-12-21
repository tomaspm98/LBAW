@if (count($questions_followed) != 0)
<div class="hover-container">
    <div class="header-container">
            <i class="fa-solid fa-circle-question" style="color: #0f4aa8;"></i>
            <span class="hover-text">Personal Feed presents you the questions you follow, so that you have more interest. It eases the way to access the more interesting questions according to each user.</span>
            <h2 class="me-md-3 mb-2 mb-md-0">Personal Feed</h2>
    </div>
</div>
<ul>
    @foreach ($questions_followed as $question)
        @if($question->content_is_visible)
        <li class="card p-2 question_card">
            <div class="row no-gutters">
            <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                <a href="{{ route('member.show', $question->author) }}"> 
                    <div class="question_user_photo">
                        @php
                            $authorPicturePath = 'public/pictures/' . $question->author->username . '/profile_picture.png';
                            $authorPicture = Storage::exists($authorPicturePath) ? asset('storage/pictures/' . $question->author->username . '/profile_picture.png') : asset('storage/pictures/default/profile_picture.png');
                        @endphp
                        <img src="{{ $authorPicture }}" alt="Profile Photo">
                    </div>
                </a>
                <div class="mt-2">
                <p class="mb-0"><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <div class="top_questions_tittle">
                        <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('questions.show', $question->question_id) }}"> <h3>{{ Str::limit($question->question_title, 50) }}</h3></a>     
                        <p class="question-text">{{ Str::limit($question->content_text, 70) }}   
                            @if(strlen($question->content_text > 50))
                            <a class="more_details" href="{{ route('questions.show', $question->question_id) }}"> more details</a>  
                            @endif
                        </p>          
                    </div>
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
        </li>
        @endif
    @endforeach
</ul>

@endif