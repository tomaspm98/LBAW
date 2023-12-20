<?php

namespace App\Http\Controllers;

use App\Events\NotificationsUpdated;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;

class AnswerController extends Controller
{
    public function createAnswer(Request $request, $question_id)
    {

        $validatedData = $request->validate([
            'content_text' => 'required|string',
        ]);

        $this->authorize('create', [Answer::class, $question_id]); 

        $answer = new Answer();
        $answer->content_text = $validatedData['content_text'];
        $answer->question_id = $question_id;
        $answer->content_author = Auth::user()->user_id;
        $answer->save();
        
        // TODO
        // $userId = Question::findOrFail($question_id)->content_author;
        $userId = Auth::id();  
        $pusherNotifications = Notification::where('notification_user', $userId)->where('notification_is_read', false)->get();
        event(new NotificationsUpdated(Auth::user(), $pusherNotifications));
        
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer created successfully');
    
}

    public function editShow($question_id, $answer_id)
    {
        $answer = Answer::findOrFail($answer_id);
        $this->authorize('edit', $answer);

        return view('pages.edit_answer', [
            'answer' => $answer
        ]);
    }

    public function update(Request $request, $question_id, $answer_id)
    {

        $validatedData = $request->validate([
            'content_text' => 'required|string',
        ]);

        $answer = Answer::findOrFail($answer_id);
        
        $this->authorize('edit', $answer);

        $validatedData['content_is_edited'] = 'true';
        $answer->update($validatedData);
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer updated successfully');
    }

    public function delete($question_id, $answer_id)
    {
        $answer = Answer::findOrFail($answer_id);
        $this->authorize('delete', $answer);

        $validatedData['content_is_visible'] = 'false';
        $answer->update($validatedData);
        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer deleted successfully');
    }

    public function correctAnswer($question_id, $answer_id)
    {
        //$this->authorize('mark_answer_correct', $question_id);
        if (Auth::user()->user_id === Question::findOrFail($question_id)->content_author) {
            $answer = Answer::findOrFail($answer_id);

            $question = Question::findOrFail($question_id);
            $validatedData['correct_answer'] = $answer_id;
            $question->update($validatedData);
            return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer corrected successfully');
        }
        else {
            return redirect()->route('questions.show', ['question_id' => $question_id])->with('error', 'You are not authorized to mark this answer as correct');
        }
    }
}
