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

class VoteController extends Controller
{
    public function createVoteQuestion(Request $request, $question_id)
    {
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
                // If the same button is pressed, set vote to 'out'
                $currentVote->upvote = 'out';
            } else {
                // Change the vote type
                $currentVote->upvote = $validatedData['upvote'];
            }
            $currentVote->vote_date = $formattedNow;
            $currentVote->save();
        } else {
            // Create a new vote
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
                // If the same button is pressed, set vote to 'out'
                $currentVote->upvote = 'out';
            } else {
                // Change the vote type
                $currentVote->upvote = $validatedData['upvote'];
            }
            $currentVote->vote_date = $formattedNow;
            $currentVote->save();
        } else {
            // Create a new vote
            $vote = new Vote();
            $vote->upvote = $validatedData['upvote'];
            $vote->vote_content_answer = $answer_id;
            $vote->entity_voted = 'answer';
            $vote->vote_author = Auth::id();
            $vote->save();
        }
    
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Vote updated successfully');
    }

    public function createVoteComment(Request $request, $question_id, $answer_id, $comment_id)
    {
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
                // If the same button is pressed, set vote to 'out'
                $currentVote->upvote = 'out';
            } else {
                // Change the vote type
                $currentVote->upvote = $validatedData['upvote'];
            }
            $currentVote->vote_date = $formattedNow;
            $currentVote->save();
        } else {
            // Create a new vote
            $vote = new Vote();
            $vote->upvote = $validatedData['upvote'];
            $vote->vote_content_comment = $comment_id;
            $vote->entity_voted = 'comment';
            $vote->vote_author = Auth::id();
            $vote->save();
        }
    
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Vote updated successfully');
    }
}
