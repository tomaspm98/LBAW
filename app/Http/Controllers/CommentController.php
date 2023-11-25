<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Log;

class CommentController extends Controller
{
    public function createComment(Request $request, $question_id, $answer_id)
    {
            
            $validatedData = $request->validate([
                'content_text' => 'required|string',
            ]);
    
            $this->authorize('create', [Comment::class]); 
            
            $comment = new Comment();
            $comment->content_text = $validatedData['content_text'];
            $comment->answer_id = $answer_id;
            $comment->content_author = Auth::user()->user_id;
            $comment->save();
    
            return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Comment created successfully');
    }

    public function editShow($question_id, $answer_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $this->authorize('edit', $comment);

        return view('pages.edit_comment', [
            'comment' => $comment
        ]);
    }

    public function update(Request $request, $question_id, $answer_id, $comment_id)
    {

        $validatedData = $request->validate([
            'content_text' => 'required|string',
        ]);

        $comment = Comment::findOrFail($comment_id);
        
        $this->authorize('edit', $comment);

        $validatedData['content_is_edited'] = 'true';
        $comment->update($validatedData);
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Comment updated successfully');
    }

    public function delete($question_id, $answer_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $this->authorize('delete', $comment);

        $validatedData['content_is_visible'] = 'false';
        $comment->update($validatedData);
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Comment deleted successfully');
    }
}