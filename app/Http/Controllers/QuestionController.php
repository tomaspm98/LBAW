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
        $validatedData['content_is_edited'] = 'true';

        $question->update($validatedData);

        return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Question updated successfully');
    }

    public function delete($question_id)
    {
        $question = Question::findOrFail($question_id);

        $validatedData['content_is_visible'] = 'false';
        $question->update($validatedData);

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
