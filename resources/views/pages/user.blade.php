@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="profile-header">
            <h2><strong>{{ $member->username }} Profile Page</strong></h2>
            <img class="profile-picture" src="{{ Storage::url($member->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
        </div>
        
        <div class="profile-details">
            <div class="detail-section">
                <h3>Email:</h3>
                <p>{{ $member->user_email }}</p>
            </div>

            <div class="detail-section">
                <h3>Score:</h3>
                <p>{{ $member->user_score }}</p>
            </div>

            <div class="detail-section">
                <h3>Birthdate:</h3>
                <p>{{ \Carbon\Carbon::parse($member->user_birthdate)->format('Y-m-d') }}</p>
            </div>
        </div>

        <div classs="badges-section">
            <h3>Badges:</h3>
            <div class="badges">
                {{-- @foreach ($member->badges as $badge)
                    <div class="badge">
                        <img src="{{ Storage::url($badge->badge_picture) }}" alt="Badge Picture">
                        <p>{{ $badge->badge_name }}</p>
                    </div>
                @endforeach --}}
            </div>
        </div>



        <div class="user-activity">
            <div class="activity-section">
                <h3 class="toggle-section"> {{ $member->questions_count }} Questions <span class="arrow">&#9660;</span></h3>
                <div class="activity-content hidden-content">
                    @foreach ($member->questions as $index => $question)
                    <div class="activity-item" style="background-color: {{ $index % 2 === 0 ? '#f0f0f0' : '#e0e0e0' }}">
                        <a href="{{ route('questions.show', $question->question_id) }}"> {{ $question->question_title }}</a>     
                        </div>                
                    @endforeach 
                </div>
            </div>

            <div class="activity-section">
                <h3 class="toggle-section"> {{ $member->answer_count }} Answers <span class="arrow">&#9660;</span></h3>
                <div class="activity-content hidden-content">
                    @foreach ($member->answers as $index => $answer)
                        <div class="activity-item" style="background-color: {{ $index % 2 === 0 ? '#f0f0f0' : '#e0e0e0' }}">
                            <a href="{{ route('questions.show', $answer->question->question_id) }}">
                                <span class="question-title">Question title:</span> {{ $answer->question->question_title }}
                            </a>
                            <p><span class="your-answer">Your answer:</span> {{ $answer->content_text }}</p>
                        </div>
                    @endforeach
                
                </div>
            </div>

            <div class="activity-section">
                <h3 class="toggle-section"> {{ $member->comments_count }} Comments <span class="arrow">&#9660;</span></h3>
                <div class="activity-content hidden-content">
                    @foreach ($member->comments as $index => $comment)
                        <div class="activity-item" style="background-color: {{ $index % 2 === 0 ? '#f0f0f0' : '#e0e0e0' }}">
                            <a href="{{ route('questions.show', $comment->answer->question->question_id) }}">
                                <span class="question-title">Question title:</span> {{ $comment->answer->question->question_title }}
                            </a>
                            <p><span class="your-answer">Answer:</span> {{ $comment->answer->content_text }}</p>
                            <p><span class="your-comment">Your comment:</span> {{ $comment->content_text }}</p>
                        </div>
                    @endforeach
                
                </div>
            </div>



        </div>






    </div>




<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSections = document.querySelectorAll('.toggle-section');
    
    toggleSections.forEach(section => {
        section.addEventListener('click', function() {
            this.classList.toggle('show-content');
            const arrow = this.querySelector('.arrow');
            arrow.classList.toggle('rotate-arrow');
            const content = this.nextElementSibling; 
            content.classList.toggle('hidden-content');
        });
    });
});
</script>


@endsection

