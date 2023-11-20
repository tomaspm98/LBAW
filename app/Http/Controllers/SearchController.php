<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Vote;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = strtolower($request->input('search')); 
        $selectedTag = $request->input('tag'); 
        $orderBy = $request->input('orderBy');


        // Save the search value
        Session::put('searchTerm', $searchTerm);

        $questions = Question::filter([
            'search' => $searchTerm,
            'tag' => $selectedTag,
            'orderBy' => $orderBy,
        ])->paginate(10);

        $totalResults = $questions->total();

        $tags = Tag::all(); 

        return view('pages.search', [
            'questions' => $questions,
            'tags' => $tags,
            'selectedTag' => $selectedTag,
            'totalResults' => $totalResults,
            'orderBy' => $orderBy,
        ])->withInput($request->only('search'));
    }
}
