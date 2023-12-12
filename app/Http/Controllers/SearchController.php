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
        // Inputs
        $searchTerm = $request->input('search'); 
        // Save the search value
        Session::put('searchTerm', $searchTerm);
        $searchTerm = strtolower($searchTerm); 

        $selectedTag = $request->input('tag'); 
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDirection');


        $questions = Question::filter([
            'search' => $searchTerm,
            'tag' => $selectedTag,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
        ])->paginate(50);

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
