<?php 
use App\Models\Moderator;

?>

<div> <!--Question-->

    <div class="d-flex flex-column flex-md-row align-items-center mt-4">

        <div class="text-center">
            <a href="{{ route('member.show', $question->author) }} " class="text-decoration-none"> <!-- route('member.show', $question->author) -->
                <div class="content_user_profile_photo">
                    <img src="{{ Storage::url($question->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                </div>
                <p><b class="text-dark">{{ $question->author->username }}</b></p>
            </a>

        </div>
        
        <div class="question_tittle_container p-3">

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
        </div>

    </div>


    <div class="content_bottom_container d-flex">

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

                
            @if(Auth::check() && Auth::id()===$question->content_author) <!-- TODO: restrict access only for owner -->
            <div class="content_right_container"> 
                <form method="POST" action="{{ route('questions.delete', $question->question_id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger"  type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                </form>
                <form method="GET" action="{{ route('questions.edit', $question->question_id) }}">
                    @csrf
                    @method('GET')
                    <button class="btn btn-outline-warning"> Edit </button>
                </form>    
            </div>
            @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
            <div class="content_right_container"> 
                <form method="POST" action="{{ route('questions.delete', $question->question_id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger mt-2" type="submit" onclick="return confirm('Are you sure you want to delete this question?')">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </div>

            @else

            <div>
                <button class="button_report btn btn-danger" id="showReportForm"> 
                    Report
                </button>
                <form id="reportForm" method="GET" action="{{ route('report.question', ['question_id' => $question->question_id]) }}" style="display: none">
                    <div class="form-group"> 
                        @csrf
                        <select name="report_reason" id="report_reason" required>
                            <option value="" disabled selected>Select reason</option>
                            <option value="spam">Spam</option>
                            <option value="offensive">Offensive</option>
                            <option value="Rules Violation">Rules Violation</option>
                            <option value="Inappropriate tag">Inappropriate tag</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="report_text">Question Content</label>
                        <textarea name="report_text" placeholder="Additional text (optional)"></textarea>
                    </div>
                    <button type="submit" class="button_report btn btn-danger" onclick="showNotification()">Submit Report</button>
                </form>
            </div>
            
            @endif
        </div>

        <div class="content_text_container border-bottom bg-light w-100 p-2">
            <p>{{ $question->content_text }}</p>
        </div>
        
    </div>

</div>
