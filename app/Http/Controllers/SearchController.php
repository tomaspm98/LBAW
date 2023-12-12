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
        $exactMatch = $request->input('exactMatch');
        
        // Save the search value
        Session::put('searchTerm', $searchTerm);
        if($exactMatch){
            $tempSearchTerm = strtolower($searchTerm); 
            $questions = Question::where(function($query) use ($tempSearchTerm) {
                $query->whereRaw('LOWER(question_title) LIKE ?', ['%' . $tempSearchTerm . '%'])
                    ->orWhereRaw('LOWER(content_text) LIKE ?', ['%' . $tempSearchTerm . '%']);
            })->paginate(50);
        }else{
            $questions = Question::filter([
                'search' => $searchTerm,
                'tag' => $selectedTag,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ])->paginate(50);
        }
        $totalResults = $questions->total();

        $tags = Tag::all(); 
        //If order by is not set make it relevance
        
        return view('pages.search', [
            'questions' => $questions,
            'tags' => $tags,
            'selectedTag' => $selectedTag,
            'totalResults' => $totalResults,
            'orderBy' => $orderBy,
        ])->with(['search' => $request->input('search')]);
    }
}
