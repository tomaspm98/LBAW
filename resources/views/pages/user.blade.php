<?php
use App\Models\UserBadge;
?>	


@extends('layouts.app')

<?php 
use App\Models\Admin; 
use App\Models\Moderator; 

?>

@section('content')
    <div class="container">

        <div class="row my-4">
            <div class="col-md-2 w-1">
                <img class="profile-picture img-fluid" src="{{ $member->getProfileImage() }}" alt="Profile Photo">
            </div>
            <div class="col-md-9">
                <h3 class="mb-3 p-1">
                    <strong>
                        {{ $member->username }}
                        @if ( admin::where('user_id', $member->user_id)->exists() )
                            <i title="Admin" class="bi bi-patch-check-fill text-primary"></i>
                        @endif
                        @if ($member->user_blocked)
                            User Currently Blocked
                        @endif
                    </strong>
                </h3>

                <div class="row profile-details">
                    <div class="col-md-4">
                        <p class="text-muted d-flex align-items-center"><i class="bi bi-envelope m-1"></i> {{ $member->user_email }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted d-flex align-items-center"><i class="bi bi-cake2 m-1"></i> {{ \Carbon\Carbon::parse($member->user_birthdate)->format('Y-m-d') }}</p>
                    </div>
                    <div class="col-md-4">
                        <h4>Score: <b class="text-muted">{{ $member->user_score }}</b></h4>
                    </div>

                </div>
            </div>
        </div>

        <div class="badges-section">
            <h3><b>Badges:</b></h3>
            <div class="badge-grid">
            @foreach ($member->badges as $badge)
                <div class="badge-unique" >
                <div class="badge-content">
                    <b>{{ $badge->badge_name }}</b><br>
                    <b>Description: </b>{{ $badge->badge_description }}
                    </div>
                <div class="badge-picture">
                    <p><img class="badge-picture" src="{{ $badge->getBadgeImage() }}" style="width: 150px; height: 150px;" alt="Badge picture"></p>
                </div>
            </div>
                
            @endforeach
            </div>
        </div>

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="activity-section">
                        <h4 class="toggle-section"> {{ $member->questions_count }} Questions <span class="arrow">&#9660;</span></h4>
                        <div class="activity-content hidden-content">
                            @foreach ($member->questions as $index => $question)
                            <div class="activity-item bg-light border-bottom p-1">
                                <a href="{{ route('questions.show', $question->question_id) }}"> {{ Str::limit($question->question_title, 100) }}</a>     
                            </div>
                            @endforeach 
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="activity-section">
                        <h4 class="toggle-section"> {{ $member->answer_count }} Answers <span class="arrow">&#9660;</span></h4>
                        <div class="activity-content hidden-content">
                            @foreach ($member->answers as $index => $answer)
                                <div class="activity-item bg-light border-bottom p-1">
                                    <a href="{{ route('questions.show', $answer->question->question_id) }}#answer-{{$answer->answer_id}}">
                                        <span class="text-primary">Question title:</span> {{ Str::limit($answer->question->question_title, 80) }}
                                    </a>
                                    <p class="p-2"><span class="text-success">Answer:</span> {{ Str::limit($answer->content_text, 150) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="activity-section">
                        <h4 class="toggle-section"> {{ $member->comments_count }} Comments <span class="arrow">&#9660;</span></h4>
                        <div class="activity-content hidden-content">
                            @foreach ($member->comments as $index => $comment)
                                <div class="activity-item bg-light border-bottom p-1">
                                    <a href="{{ route('questions.show', $comment->answer->question->question_id) }}#comment-{{$comment->comment_id}}">
                                        <span class="text-primary">Question title:</span> {{ Str::limit($comment->answer->question->question_title, 80) }}
                                    </a>
                                    <p class="p-2"><span class="text-success">Answer:</span> {{ Str::limit($comment->answer->content_text,150) }}</p>
                                    <p class="p-2"><span class="text-info">Comment:</span> {{ Str::limit($comment->content_text,150) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
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

