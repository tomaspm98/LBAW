<?php 
use App\Models\Moderator;

?>

<div id="answer-{{$answer->answer_id}}" class="my-4"> <!--answer-->

    <div class="content_bottom_container d-flex">

        <div id="action_buttons" class="text-center p-2">

            <div class="text-center">
                <a href="{{ route('member.show', $answer->author) }}">
                    <div class="content_user_profile_photo">
                        <!-- <img src="{{ Storage::url($answer->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo"> -->
                    </div>
                </a>
                <p><b>{{ $answer->author->username }}</b></p>
            </div>

            @if (Auth::check() && Auth::id() === $question->content_author) 
            <div class="correct_answer">
                <form action="{{ route('answers.correct', ['question_id' => $question->question_id, 'answer_id' => $answer -> answer_id]) }}" method="POST">
                    @csrf
                    @php $correct = $question->correct_answer; @endphp
                    <button type="submit" onclick="showSuccessMessage()" class="btn {{ $correct && $correct == $answer->answer_id ? 'btn-cor_answer' : 'btn-primary' }}">Mark as Correct</button>
                </form>
            </div>
            @endif

            <form action="{{ route('votes.voteAnswer', ['question_id' => $question->question_id, 'answer_id' => $answer->answer_id]) }}#answer-{{$answer->answer_id}}" method="POST">
                @csrf
                @php $userVote = $answer->userVote; @endphp
                <button type="submit" name="upvote" value="up" class="btn p-2 rounded-top-5 {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">
                    <i class="bi bi-caret-up-fill"></i> <!--like-->
                </button>
                <p class="mt-3"><b>{{$answer->vote_count}}</b></p>
                <button type="submit" name="upvote" value="down" class="btn p-2 rounded-bottom-5 {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">
                    <i class="bi bi-caret-down-fill"></i> <!--dislike-->
                </button>
            </form>

                
            @if(Auth::check() && Auth::id()===$answer->content_author) 
            <div class="content_right_container"> 
            <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger"  type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                </form>
                <form method="GET" action="{{ route('answers.edit', [$question->question_id, $answer->answer_id]) }}">
                    @csrf
                    @method('GET')
                    <button class="btn btn-outline-warning"> Edit </button>
                </form>    
            </div>
            
            @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
            <div class="content_right_container"> 
                <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit">Delete</button>
                </form>
            </div> 
            @else 

            <div>
                <button class="button_report btn btn-danger mt-2" id="showReportAnswerForm"> 
                    Report
                </button>
                <form id="reportAnswerForm" method="POST" action="{{ route('report.answer', ['answer_id' => $answer->answer_id]) }}#answer-{{$answer->answer_id}}" style="display: none">
                    <div class="form-group"> 
                        @csrf
                        <select name="report_reason" id="report_reason_answer" required>
                            <option value="" disabled selected>Select reason</option>
                            <option value="spam">Spam</option>
                            <option value="offensive">Offensive</option>
                            <option value="Rules Violation">Rules Violation</option>
                            <option value="Inappropriate tag">Inappropriate tag</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="report_text">Answer Content</label>
                        <textarea name="report_text" placeholder="Additional text (optional)"></textarea>
                    </div>
                    <button type="submit" class="button_report btn btn-danger_answer" onclick="showNotificationAnswer()">Submit Report</button>
                </form>
            </div>
            
            @endif

        </div>

        <div class="w-100 bg-light border-bottom rounded-2">
            <div class="answer_container p-2">

                <p>
                    <strong>Created at: </strong>{{\Carbon\Carbon::parse($answer->content_creation_date)->format('Y-m-d')}}
                    @if($answer->content_is_edited)
                    <br>
                    <span class="text-warning">edited</span>
                    @endif
                </p>
            </div>

            <div class="content_text_container w-100 p-2">
                <p>{{ $answer->content_text }}</p>
            </div>
        </div>
    </div>
</div>