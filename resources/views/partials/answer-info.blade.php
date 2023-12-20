<?php 
use App\Models\Moderator;

?>

<div id="answer-{{$answer->answer_id}}" class="my-4 d-flex "> <!--answer-->
    <div id="action_buttons" class="text-center p-2">
        <div class="text-center">
            <a href="{{ route('member.show', $answer->author) }}">
                <div class="content_user_profile_photo">
                @php
                    $authorPicturePath = 'public/pictures/' . $answer->author->username . '/profile_picture.png';
                    $authorPicture = Storage::exists($authorPicturePath) ? asset('storage/pictures/' . $answer->author->username . '/profile_picture.png') : asset('storage/pictures/default/profile_picture.png');
                @endphp
                <img src="{{ $authorPicture }}" alt="Profile Photo">
                </div>
            </a>
            <p><b>{{ $answer->author->username }}</b></p>
        </div>

        <form id="voteForm" method="POST">            
            @csrf
            @php $userVote = $answer->userVote; @endphp
            <button type="button" data-vote="up" data-answer-id="{{$answer->answer_id}}" class="vote-btn-answer btn p-2 rounded-top-5 {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">
                <i class="bi bi-caret-up-fill"></i> <!--like-->
            </button>
            <p class="mt-3"><b id="voteCountAnswer{{$answer->answer_id}}">{{$answer->vote_count}}</b></p>
            <button type="button" data-vote="down" data-answer-id="{{$answer->answer_id}}" class="vote-btn-answer btn p-2 rounded-bottom-5 {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">
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
            @elseif (Auth::check())
            {{-- Verificar se a tag da pergunta é diferente da tag pela qual o moderator é responsavel --}}
            {{-- @if (Moderator::where('user_id', Auth::user()->user_id)->exists() && $question->tag->tag_name !== Auth::user()->moderator->tag->tag_name) --}}
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
            <form id="reportAnswerForm" method="POST" action="{{ route('report.answer', ['answer_id' => $answer->answer_id]) }}#answer-{{$answer->answer_id}}">
                @csrf
                <select name="report_reason" id="report_reason_answer" required>
                    <option value="" disabled selected>Select reason</option>
                    <option value="spam">Spam</option>
                    <option value="offensive">Offensive</option>
                    <option value="Rules Violation">Rules Violation</option>
                    <option value="Inappropriate tag">Inappropriate tag</option>
                </select>
                <label for="report_text">Answer Content</label>
                <textarea name="report_text" placeholder="Additional text (optional)"></textarea>
                <button type="submit" class="button_report btn btn-danger_answer" onclick="showNotificationAnswer()">Submit Report</button>
            </form>
        </div>
        @endif

        <div class="position-relative bg-light border-bottom rounded-2 m-2 w-100">
            <div class="answer_container p-2">
                <p>
                    <strong>Created at: </strong>{{\Carbon\Carbon::parse($answer->content_creation_date)->format('Y-m-d')}}
                    @if ($question->correct_answer === $answer->answer_id)
                    <b class="text-success"> [ Correct Answer ]</b>  
                    @endif

                    @if($answer->content_is_edited)
                    <br>
                    <span class="text-warning">edited</span>
                    @endif
                </p>
            </div>

            <div class="content_text_container p-2">
                <p>{{ $answer->content_text }}</p>
            </div>

            @if (Auth::check())
            <div class="dropleft position-absolute top-0 end-0 m-2" >
                <button class="btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-three-dots fs-5"></i>
                </button>

                <ul class="dropdown-menu">
                    
                    @csrf
                    @php $correct = $question->correct_answer; @endphp
                    @if (Auth::check() && Auth::id() === $question->content_author && $question->correct_answer !== $answer->answer_id) 
                    <li class="correct_answer">
                        <form action="{{ route('answers.correct', ['question_id' => $question->question_id, 'answer_id' => $answer -> answer_id]) }}" method="POST">
                            @csrf
                            @php $correct = $question->correct_answer; @endphp
                            <button type="submit" onclick="showSuccessMessage()" class="btn dropdown-item text-success {{ $correct && $correct == $answer->answer_id ? 'btn-cor_answer' : 'btn-primary' }}">Mark as Correct</button>
                        </form>
                    </li>
                    @endif

                    @if(Auth::check() && Auth::id()===$answer->content_author) 
                    <li> 
                        <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn dropdown-item text-danger" type="submit">Delete</button>
                        </form>
                        <form method="GET" action="{{ route('answers.edit', [$question->question_id, $answer->answer_id]) }}">
                            @csrf
                            <button> 
                                Edit
                            </button>
                        </form> 
                    </li>

                    @elseif (Auth::check())
                    {{-- Verificar se a tag da pergunta é diferente da tag pela qual o moderator é responsavel --}}
                    {{-- @if (Moderator::where('user_id', Auth::user()->user_id)->exists() && $question->tag->tag_name !== Auth::user()->moderator->tag->tag_name) --}}
                    <li class="btn-group text-danger">
                        <button id="showReportAnswerForm" class="dropdown-item button_report btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#AnswerModal" data-bs-whatever="@mdo">
                            Report Answer
                        </button>
                    </li> 
                    @endif

                    @if (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
                    <li class="content_right_container"> 
                        <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                            @csrf
                            @method('DELETE')
                            <button class="dropdown-item text-danger" type="submit">Delete</button>
                        </form>
                    </li> 
                    @endif 
                </ul>
            </div>
        </div>
        
        <div class="modal fade" id="AnswerModal" tabindex="-1" aria-labelledby="AnswerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="AnswerModalLabel">Report Question</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                    <form  id="reportAnswerForm" method="POST" action="{{ route('report.answer', ['answer_id' => $answer->answer_id]) }}">
                        <div class="form-group mb-3Z">
                            @csrf
                            <select name="report_reason" id="report_reason_answer" required>
                                <option value="" disabled selected>Select reason</option>
                                <option value="spam">Spam</option>
                                <option value="offensive">Offensive</option>
                                <option value="Rules Violation">Rules Violation</option>
                                <option value="Inappropriate tag">Inappropriate tag</option>
                            </select>
                        </div>
                        <div class="form-group my-2">
                            <textarea class="form-control fixed-height" name="report_text" placeholder="Additional text (optional)" rows="4"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="button_report_answer btn btn-primary" data-bs-dismiss="modal" onclick="showNotificationAnswer()">Submit Report</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        @endif
    </div>
</div>
    