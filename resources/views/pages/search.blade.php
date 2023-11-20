@extends('layouts.app')

@section('content')

<div class="container">

    <form class="search_form" action="{{ route('search') }}" method="GET">

        <label for="search">Search:</label>
        <input type="text" name="search" value="{{ Session::get('searchTerm') }}" placeholder="Search...">

        <label for="tag">Filter by Tag name:</label>
        <select name="tag">
            <option value="all">All tags</option>
            @foreach ($tags as $tag)
                <option value="{{ $tag->tag_name }}" {{ $selectedTag == $tag->tag_name ? 'selected' : '' }}>
                    {{ $tag->tag_name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Search</button>
    </form>

    <!-- <b>Sort by:</b>
    <div class="sort_container">

        <button>
            <a href="{{ route('search', ['orderBy' => 'date', 'orderDirection' => 'asc']) }}">most recent</a>
        </button>
        <button>
            <a href="{{ route('search', ['orderBy' => 'date', 'orderDirection' => 'desc']) }}">oldest</a>
        </button>
    </div> -->




    @if($totalResults == 1)
    <div>{{$totalResults}} result:</div>
    @else
    <div>{{$totalResults}} results:</div>
    @endif

    <ul class="questions_results_container">
        @forelse ($questions as $question)
            <li class="question_card">

                <div class="question_user_container">
                    <a href=""> <!-- route('member.show', $question->author) -->
                        <div class="question_user_photo">
                            <img src="" alt="Profile Photo">
                        </div>
                    </a>
                    <p><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                </div>
                
                <div class="top_questions_tittle">
                    <b>Tag:</b> {{ $question->tag->tag_name }}
                    <a href="{{ route('questions.show', $question->question_id) }}"> <h3>{{ $question->question_title }}</h3></a>    
                    <p class="question-text">{{ Str::limit( $question->content_text ,50)}}   
                        @if(strlen($question->content_text > 50))
                        <a class="more_details" href="{{ route('questions.show', $question->question_id) }}"> more details</a>  
                        @endif
                    </p>         
                </div>

                <div class="top_questions_n_answers">
                    @if($question->answer_count !== 1)
                    <p>{{$question->answer_count}} answers</p>
                    @else
                    p>{{$question->answer_count}} answer</p>
                    @endif
                </div>

                <div class="top_questions_votes">
                    <p>{{$question->vote_count}} votes</p> 
                </div>
            </li>
        @empty
        <div class="no_result_message">
            <p>No results found. Please check back later.</p>
        </div>
        @endforelse
    </ul>

</div>

@endsection
