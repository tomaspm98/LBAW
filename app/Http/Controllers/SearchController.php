<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Vote;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){

        $searchTerm = strtolower($request->input('search'));
        $tag = $request->input('Tag');

        $currentTag = Tag::firstWhere('tag_id', $tag);

        $questions = Question::filter(['search' => $searchTerm, 'tag' => $tag])->paginate(10);
        $tags = Tag::all();

        // Append filter parameters to the pagination links
        $questions->appends(['search' => $searchTerm, 'tag' => $tag]);

        return view('pages.search',[
            'questions' => $questions,
            'tags' => $tags,
            'selectedTag' => $currentTag
        ]);
    }
}
