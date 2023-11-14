<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use Illuminate\View\View;

class AnswerController extends Controller
{
    public function createAnswer(Request $request, $question_id)
    {
        $validatedData = $request->validate([
            'content_text' => 'required|string',
        ]);

        $answer = new Answer();
        $answer->content_text = $validatedData['content_text'];
        $answer->question_id = $question_id;
        $answer->content_author = '1';
        $answer->save();

        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer created successfully');
    }

    public function editShow($question_id, $answer_id): View
    {
        $answer = Answer::findOrFail($answer_id);

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

        $answer->update($validatedData);

        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer updated successfully');
    }

    public function delete($question_id, $answer_id)
    {
        $answer = Answer::findOrFail($answer_id);

        $answer->comments()->delete();
        if ($answer->reports()->exists()) {  // Check if there are reports
            $answer->reports()->delete();
        }
        if ($answer->votes()->exists()) {  // Check if there are reports
            $answer->votes()->delete();
        }
        $answer->delete();

        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Answer deleted successfully');
    }
}
