<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Comment;
use App\Models\Member;
use App\Models\Vote;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
    public function createVoteQuestion(Request $request, $question_id)
    {
        if(Auth::check() == false) {
            return response()->json(['message' => 'You must be logged in to vote', 'voteCount' => Question::find($question_id)->vote_count]);
        }

        $validatedData = $request->validate([
            'upvote' => 'required|string',
        ]);
    
        $this->authorize('create', Vote::class);
    
        $currentVote = Vote::where('vote_content_question', $question_id)
                           ->where('vote_author', Auth::id())
                           ->latest('vote_date')
                           ->first();
        
        $formattedNow = now()->format('Y-m-d H:i:s.u');

        if ($currentVote) {
            if ($validatedData['upvote'] == $currentVote->upvote) {
            
                $currentVote->upvote = 'out';
            } else {
                $currentVote->upvote = $validatedData['upvote'];
            }
            $currentVote->vote_date = $formattedNow;
            $currentVote->save();
        } else {
            $vote = new Vote();
            $vote->upvote = $validatedData['upvote'];
            $vote->vote_content_question = $question_id;
            $vote->entity_voted = 'question';
            $vote->vote_author = Auth::id();
            $vote->save();
        }
        $updatedVoteCount = Question::find($question_id)->vote_count;
        return response()->json(['message' => 'Vote updated successfully', 'voteCount' => $updatedVoteCount]);
        }

    public function createVoteAnswer(Request $request, $question_id, $answer_id)
    {
        if(Auth::check() == false) {
            return response()->json(['message' => 'You must be logged in to vote', 'voteCount' => Answer::find($answer_id)->vote_count]);
        }

        $validatedData = $request->validate([
            'upvote' => 'required|string',
        ]);
    
        $this->authorize('create', Vote::class);
    
        $currentVote = Vote::where('vote_content_answer', $answer_id)
                           ->where('vote_author', Auth::id())
                           ->latest('vote_date')
                           ->first();
        
        $formattedNow = now()->format('Y-m-d H:i:s.u');

        if ($currentVote) {
            if ($validatedData['upvote'] == $currentVote->upvote) {
                $currentVote->upvote = 'out';
            } else {
                $currentVote->upvote = $validatedData['upvote'];
            }
            $currentVote->vote_date = $formattedNow;
            $currentVote->save();
        } else {
            $vote = new Vote();
            $vote->upvote = $validatedData['upvote'];
            $vote->vote_content_answer = $answer_id;
            $vote->entity_voted = 'answer';
            $vote->vote_author = Auth::id();
            $vote->save();
        }
    
        $updatedVoteCount = Answer::find($answer_id)->vote_count;
        Log::info($updatedVoteCount);
        $answer = Answer::find($answer_id);
        return response()->json(['message' => 'Vote updated successfully', 'voteCount' => $updatedVoteCount]);
    }

    public function createVoteComment(Request $request, $question_id, $answer_id, $comment_id)
    {
        if(Auth::check() == false) {
            return response()->json(['message' => 'You must be logged in to vote', 'voteCount' => Comment::find($comment_id)->vote_count]);
        }

        $validatedData = $request->validate([
            'upvote' => 'required|string',
        ]);
    
        $this->authorize('create', Vote::class);
    
        $currentVote = Vote::where('vote_content_comment', $comment_id)
                           ->where('vote_author', Auth::id())
                           ->latest('vote_date')
                           ->first();
        
        $formattedNow = now()->format('Y-m-d H:i:s.u');

        if ($currentVote) {
            if ($validatedData['upvote'] == $currentVote->upvote) {
                $currentVote->upvote = 'out';
            } else {
                $currentVote->upvote = $validatedData['upvote'];
            }
            $currentVote->vote_date = $formattedNow;
            $currentVote->save();
        } else {
            $vote = new Vote();
            $vote->upvote = $validatedData['upvote'];
            $vote->vote_content_comment = $comment_id;
            $vote->entity_voted = 'comment';
            $vote->vote_author = Auth::id();
            $vote->save();
        }
        
        $updatedVoteCount = Comment::find($comment_id)->vote_count;

        return response()->json(['message' => 'Vote updated successfully', 'voteCount' => $updatedVoteCount]);
    }
}
