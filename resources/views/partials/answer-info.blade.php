<?php 
use App\Models\Moderator;

?>

<div id="answer-{{$answer->answer_id}}" class="my-4 d-flex position-relative"> <!--answer-->

    <div id="action_buttons" class="text-center p-2 ">

        <div class="text-center">
            <a href="{{ route('member.show', $answer->author) }}">
                <div class="content_user_profile_photo">
                    <img src="{{ Storage::url($answer->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                </div>
            </a>
            <p><b>{{ $answer->author->username }}</b></p>
        </div>

        <div id="action_buttons" class="text-center p-2">
            <form id="voteForm" action="{{ route('votes.voteQuestion', ['question_id' => $question->question_id]) }}" method="POST">
                @csrf
                @php $userVote = $question->userVote; @endphp
                <button type="button" data-vote="up" data-answer-id="{{$answer->answer_id}}" 
                class="p-2 rounded-top-5 vote-btn-answer btn {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">
                    <i class="bi bi-caret-up-fill"></i> <!--like-->
                </button>
                <p class="mt-3"><b id="voteCountAnswer{{$answer->answer_id}}">{{$answer->vote_count}}</b></p>
                <button type="button" data-vote="down" data-answer-id="{{$answer->answer_id}}"  
                class="p-2 rounded-bottom-5 vote-btn-answer btn {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">
                    <i class="bi bi-caret-down-fill"></i> <!--dislike-->
                </button>
            </form>
        </div>

    </div>

    <div class=" bg-light w-100 border-bottom rounded-2">
        <div class="answer_container p-2">
            <p>
                <strong>Created at: </strong>{{\Carbon\Carbon::parse($answer->content_creation_date)->format('Y-m-d')}}
                @if($answer->content_is_edited)
                <br>
                <span class="text-warning">edited</span>
                @endif
            </p>
        </div>

        <div class="content_text_container p-2">
            <p>{{ $answer->content_text }}</p>
        </div>
    </div>


    <div class="dropstart position-absolute top-0 end-0 m-2" >
        <button class="btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <i class="bi bi-three-dots"></i>
        </button>
        <ul class="dropdown-menu">
            @if (Auth::check() && Auth::id() === $question->content_author) 
            <li class="correct_answer">
                <form class="m-0" action="{{ route('answers.correct', ['question_id' => $question->question_id, 'answer_id' => $answer -> answer_id]) }}" method="POST">
                    @csrf
                    @php $correct = $question->correct_answer; @endphp
                    <button class="text-success dropdown-item {{ $correct && $correct == $answer->answer_id ? 'btn-cor_answer' : 'btn-primary' }}" type="submit" onclick="showSuccessMessage()" >
                        Mark as Correct
                    </button>
                </form>
            </li>
            @endif
                
            @if(Auth::check() && Auth::id()===$answer->content_author) 
            <li>    
                <form class="m-0" action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item text-danger toggle"  type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                </form>
            </li>

            <li>
                <form class="m-0" method="GET" action="{{ route('answers.edit', [$question->question_id, $answer->answer_id]) }}">
                    @csrf
                    @method('GET')
                    <button class="dropdown-item"> Edit </button>
                </form>  
            </li>
            <li>

             
            {{-- @elseif (Auth::check()) --}}
            {{-- Verificar se a tag da pergunta é diferente da tag pela qual o moderator é responsavel --}}
            {{-- @if (Moderator::where('user_id', Auth::user()->user_id)->exists() && $question->tag->tag_name !== Auth::user()->moderator->tag->tag_name) --}}
        
            @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
            <li> 
                <form action="{{ route('answers.delete', [$question->question_id, $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this answer?')">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                </form>
            </li> 
            @else 

            <li>
                <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#AnswerModal" data-bs-whatever="@mdo">Report</button>
            </li>
            @endif

        </ul>

        <div class="modal fade" id="AnswerModal" tabindex="-1" aria-labelledby="AnswerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="AnswerModalLabel">Report answer</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="reportAnswerForm" method="POST" action="{{ route('report.answer', ['answer_id' => $answer->answer_id]) }}#answer-{{$answer->answer_id}}">
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
                                <textarea class="form-control fixed-height" style="min-height:200px;" name="report_text" placeholder="Additional text (optional)"></textarea>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="button_report btn btn-primary"  onclick="showNotificationAnswer()">Submit Report</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>