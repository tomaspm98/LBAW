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
}
