<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Events\QuestionUpdated;
use App\Models\Member;
use Carbon\Carbon;
use App\Models\UserFollowQuestion;
use App\Models\Tag;


class QuestionController extends Controller
{
    public function show($question_id)
    {

        $question = Question::findOrFail($question_id);
        $tags = Tag::all();

        return view('pages.question', [
            'question' => $question,
            'tags' => $tags
        ]);
    }

    public function editShow($question_id)
    {
        $question = Question::findOrFail($question_id);
        $this->authorize('edit', $question);
        return view('pages.edit_question', [
            'question' => $question
        ]);
    }

    public function updateTag(Request $request, Question $question)
    {
        $question->update(['question_tag' => $request->input('question_tag')]);
        // Perform necessary actions after updating the tag
        return redirect()->back()->with('success', 'Tag updated successfully');
    }

    public function update(Request $request, $question_id)
    {

        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'content_text' => 'required|string',

        ]);

        $question = Question::findOrFail($question_id);
        $this->authorize('edit', $question);
        $validatedData['content_is_edited'] = 'true';
            $question->update($validatedData);
            return redirect()->route('questions.show', ['question_id' => $question_id])->with('success', 'Question updated successfully');
    }

    public function delete($question_id)
    {
        $question = Question::findOrFail($question_id);
        $this->authorize('delete', $question);
        $validatedData['content_is_visible'] = 'false';
            $question->update($validatedData);
            return redirect()->route('home')->with('success', 'Question deleted successfully');
    }

    public function list()
    {
        $questions = Question::all();

        return view('pages.questions', [
            'questions' => $questions
        ]);
    }

    public function createShow()
    {
        $tags = Tag::all();
        return view('pages.create_question', [
            'tags' => $tags
        ]);
    }

    public function updateQuestionCount()
    {
        $totalQuestions = Question::count();

        $oneWeekAgo = Carbon::now()->subWeek()->toDateTimeString();

        $questionsLastWeek = Question::where('content_creation_date', '>=', $oneWeekAgo)->count();
        $newUsersLastWeek = Member::where('user_creation_date', '>=', $oneWeekAgo)->count();

        // Do not broadcast the event, just return the JSON response
        return response()->json([
            'totalQuestions' => $totalQuestions,
            'questionsLastWeek' => $questionsLastWeek,
            'newUsersLastWeek' => $newUsersLastWeek,
            'message' => 'Question count updated successfully'
        ]);
    }


    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'content_text' => 'required|string',
            'question_tag' => 'required|integer',
        ]);

        $this->authorize('create', Question::class);
        $validatedData['content_author'] = Auth::user()->user_id;

        $question = Question::create($validatedData);

        return redirect()->route('questions.show', ['question_id' => $question->question_id])->with('success', 'Question created successfully');
    }

    public function followQuestion(Request $request, $question_id)
    {
        $question = Question::findOrFail($question_id);
        $user_id = Auth::user()->user_id;
        $follow = UserFollowQuestion::where('user_id', $user_id)
                                    ->where('question_id', $question_id)
                                    ->first();
        $isFollowing = UserFollowQuestion::where('user_id', Auth::id())->where('question_id', $question->question_id)->exists(); 

        if ($follow) {
            $validatedData['follow'] = false;
            $follow->update($validatedData);
            return response()->json(['isFollowing' => $isFollowing]);
        } else {
            $validatedData['user_id'] = $user_id;
            $validatedData['question_id'] = $question_id;
            $validatedData['follow'] = true;
            $follow_create = UserFollowQuestion::create($validatedData);
            return response()->json(['isFollowing' => $isFollowing]);        }                            
    }
}
