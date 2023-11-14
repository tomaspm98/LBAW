<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function show($question_id): View
    {

        $question = Question::findOrFail($question_id);

        return view('pages.question', [
            'question' => $question
        ]);
    }

    public function editShow($question_id): View
    {
        $question = Question::findOrFail($question_id);

        return view('pages.edit_question', [
            'question' => $question
        ]);
    }

    public function update(Request $request, $question_id)
    {

        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'content_text' => 'required|string',

        ]);

        $question = Question::findOrFail($question_id);

        $question->update($validatedData);

        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Question updated successfully');
    }

    public function delete($question_id)
    {
        $question = Question::findOrFail($question_id);

        foreach ($question->answers as $answer) {
            $answer->comments()->delete();
            if ($answer->reports()->exists()) {  // Check if there are reports
                $answer->reports()->delete();
            }
            if ($answer->votes()->exists()) {  // Check if there are reports
                $answer->votes()->delete();
            }
            $answer->delete();
        }

        if ($question->reports()->exists()) {  // Check if there are reports
            $question->reports()->delete();
        }
        if ($question->votes()->exists()) {  // Check if there are reports
            $question->votes()->delete();
        }
        if ($question->follows()->exists()) {  // Check if there are reports
            $question->follows()->delete();
        }
        $question->delete();

        return redirect()->route('home')->with('success', 'Question deleted successfully');
    }

    public function list(): View
    {
        $questions = Question::all();

        return view('pages.questions', [
            'questions' => $questions
        ]);
    }

    public function createShow(): View
    {
        return view('pages.create_question');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'content_text' => 'required|string',
            'question_tag' => 'required|integer',
        ]);

        $validatedData['content_author'] = '1';

        $question = Question::create($validatedData);

        return redirect()->route('questions.show', ['question_id' => $question->question_id])->with('success', 'Question created successfully');
    }
}
