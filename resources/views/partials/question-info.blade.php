<?php 
use App\Models\Moderator;
use App\Models\UserFollowQuestion;
?>

<div class="d-flex mt-3"> <!--Question-->

    <div class="d-flex flex-column">

        <div class="text-center">
            <a href="{{ route('member.show', $question->author) }} " class="text-decoration-none"> <!-- route('member.show', $question->author) -->
                <div class="content_user_profile_photo">
                    <img src="{{ Storage::url($question->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                </div>
                <p><b class="text-dark">{{ $question->author->username }}</b></p>
            </a>
        </div>

        <div id="action_buttons" class="text-center p-2">
            <form action="{{ route('votes.voteQuestion', ['question_id' => $question->question_id]) }}" method="POST">
                @csrf
                @php $userVote = $question->userVote; @endphp
                <button type="submit" name="upvote" value="up" class="btn p-2 rounded-top-5 {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">
                    <i class="bi bi-caret-up-fill"></i> <!--like-->
                </button>
                <p class="mt-3"><b>{{$question->vote_count}}</b></p>
                <button type="submit" name="upvote" value="down" class="btn p-2 rounded-bottom-5 {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">
                    <i class="bi bi-caret-down-fill"></i> <!--dislike-->
                </button>
            </form>
        </div>
        
    </div>


    <div class="question_tittle_container position-relative p-3 W-100">

        <div class="d-flex flex-column flex-md-row align-items-center">
            @if ($question->tag)
            <p class="m-0"><strong>Tag:</strong> {{ $question->tag->tag_name }}</p>
            @else
            <p class="m-0"><strong>Tag:</strong> Not specified</p>
            @endif
            @if (Moderator::where('user_id', Auth::user()->user_id)->exists())
            <button id="editTagButton" class="btn btn-warning p-1 mx-4">Edit Tag</button>
                {{-- Create a button to change the tag of the question here --}}

                <div id="tagEditSection" style="display: none;">
                    <form class="d-flex align-items-center m-0" id="tagEditForm" action="{{ route('questions.updateTag', $question->question_id) }}" method="POST">
                        @csrf
                        <select class="border rounded p-1 mx-1" name="question_tag">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-success p-1" type="submit">Save</button>
                    </form>
                </div>

            @endif
        </div>

        <h2>{{ $question->question_title }}</h2>
        <p>
            <strong>Created at: </strong>{{\Carbon\Carbon::parse($question->content_creation_date)->format('Y-m-d')}}
            @if($question->content_is_edited)
            <br>
            <span class="text-warning">edited</span>
            @endif
        </p>

        <div class="content_text_container border-bottom bg-light p-2" style="min-height:135px;">
            <p>{{ $question->content_text }}</p>
        </div>

        <div class="dropdown dropleft position-absolute top-0 end-0 m-2" >
            <button class="btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <i class="bi bi-three-dots fs-5"></i>
            </button>
            <ul class="dropdown-menu">
                @if (Auth::check())    
                @php $isFollowing = UserFollowQuestion::where('user_id', Auth::id())->where('question_id', $question->question_id)->exists(); @endphp    
                <li>
                    <button class="dropdown-item text-warning" id="followQuestionButton" data-question-id="{{ $question->question_id }}">
                        {{ $isFollowing ? 'Unfollow Question' : 'Follow Question' }}
                    </button>
                </li>
                @endif

                @if(Auth::check() && Auth::id()===$question->content_author) <!-- TODO: restrict access only for owner -->
                <li>
                    <form action="{{ route('questions.delete', $question->question_id) }} " 
                    method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger"  type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                    </form>
                </li>
                <li>
                    <form method="GET" action="{{ route('questions.edit', $question->question_id) }}" class="m-0">
                        @csrf
                        <button class="dropdown-item"> 
                            Edit
                        </button>
                    </form>
                </li>

                @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                <li>
                    <form action="{{ route('questions.delete', $question->question_id) }}" 
                    method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger" type="submit" 
                        onclick="return confirm('Are you sure you want to delete this question?')">
                            Delete
                        </button>
                    </form>
                </li>

                @else
                <li>
                <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#QuestionModal" data-bs-whatever="@mdo">
                    Report Question
                </button>
                </li>  
                @endif    

            </ul>

            <div class="modal fade" id="QuestionModal" tabindex="-1" aria-labelledby="QuestionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="QuestionModalLabel">Report Question</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form class="p-2" id="reportForm" method="GET" action="{{ route('report.question', ['question_id' => $question->question_id]) }}">
                                @csrf
                                <div class="mb-3">
                                    <select class="form-select" name="report_reason" id="report_reason_answer" required>
                                        <option value="" disabled selected>Select reason</option>
                                        <option value="spam">Spam</option>
                                        <option value="offensive">Offensive</option>
                                        <option value="Rules Violation">Rules Violation</option>
                                        <option value="Inappropriate tag">Inappropriate tag</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="report_text">Answer Content</label>
                                    <textarea class="form-control fixed-height" style="min-height:200px;" name="report_text"  placeholder="Additional text (optional)" rows="4"></textarea>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="button_report btn btn-primary" data-bs-dismiss="modal" onclick="showNotificationAnswer()">Submit Report</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
