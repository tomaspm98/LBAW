<?php 
use App\Models\Moderator;

?>

<div class="comment_container w-100 bg-light mt-3 border-bottom rounded-2 p-1 d-flex position-relative" id="comment-{{$comment->comment_id}}">
    
    <form action="{{ route('votes.voteComment', ['question_id' => $question->question_id, 'answer_id' => $answer->answer_id, 'comment_id' => $comment -> comment_id]) }}#comment-{{$comment->comment_id}}" 
    method="POST" class="text-center p-2" style="width:70px"> 
        @csrf
        @php $userVote = $comment->userVote; @endphp
        <button type="submit" name="upvote" value="up" class="btn p-2 rounded-top-5 {{ $userVote && $userVote->upvote == 'up' ? 'btn-success' : 'btn-primary' }}">
            <i class="bi bi-caret-up-fill"></i> <!--like-->
        </button>
        <p class="mt-3"><b>{{$comment->vote_count}}</b></p>
        <button type="submit" name="upvote" value="down" class="btn p-2 rounded-bottom-5 {{ $userVote && $userVote->upvote == 'down' ? 'btn-danger' : 'btn-primary' }}">
            <i class="bi bi-caret-down-fill"></i> <!--dislike-->
        </button>
    </form>

    <div>
        <div>
            <span>
                <b>Commented by: </b>
                <a class="text-dark" href="">
                    {{$comment->author->username }}
                </a>
            </span>
        </div>

        <span class="mb-3" ><strong>Created at: </strong>{{\Carbon\Carbon::parse($comment->content_creation_date)->format('Y-m-d')}} </span>

        <div class="content_text_container">
            @if($comment->content_is_edited)
            <span class="text-warning">edited</span>
            @endif
        </div>

        <p>{{ $comment->content_text }}</p>

    </div>

    <div class="dropdown dropleft position-absolute top-0 end-0" >
        <button class="btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <i class="bi bi-three-dots"></i>
        </button>
        <ul class="dropdown-menu">

            @if(Auth::check() && Auth::id()===$comment->content_author) <!-- restrict access only for owner -->
            <li>
                <form action="{{ route('comments.delete', [$question->question_id, $answer->answer_id, $comment->comment_id]) }} " 
                method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                </form>
            </li>
            <li>
                <form method="GET" action="{{ route('comments.edit', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}" class="m-0">
                    @csrf
                    <button class="dropdown-item"> 
                        Edit
                    </button>
                </form>
            </li>
            
            @elseif (Auth::check() && Moderator::where('user_id', Auth::user()->user_id)->exists())
            <li>
                <form action="{{ route('comments.delete', [$question->question_id, $answer->answer_id, $comment->comment_id]) }}" 
                method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                </form>
            </li>
            
            @else
            <div class="btn-group dropstart text-danger">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Report Comment
                </button>
                <ul class="dropdown-menu p-1" style="width:300px;">
                            
                    <form class="p-2" id="reportCommentForm" method="POST" action="{{ route('report.comment', ['answer_id' =>$comment->answer->answer_id, 'comment_id' => $comment->comment_id]) }}">
                        <div class="form-group mb-1">
                            @csrf
                            <select class="form-select" name="report_reason" id="report_reason_comment" required>
                            <option value="" disabled selected>Select reason</option>
                            <option value="spam">Spam</option>
                            <option value="offensive">Offensive</option>
                            <option value="Rules Violation">Rules Violation</option>
                            <option value="Inappropriate tag">Inappropriate tag</option>
                            </select>
                        </div>
                        <div class="form-group my-2">
                            <textarea class="form-control" name="report_text" placeholder="Additional text (optional)" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Report</button>
                    </form>

                </ul>
            </div>  
            @endif    

        </ul>
    </div>

</div>
