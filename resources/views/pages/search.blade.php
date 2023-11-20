

@extends('layouts.app')

@section('content')

<div class="container">

    <form class="search_form" action="{{ route('search') }}" method="GET">

        <span for="search">Search:</span>
        <input type="text" name="search" value="{{ Session::get('searchTerm') }}" placeholder="Search...">

        <span for="tag">Filter by Tag name:</span>
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

    <b>Sort by:</b>
    <div class="sort_container">

        <div>
            <span>Creation date </span>
            <br>
            <button>
                <a href="{{ route('search', array_merge(request()->query(), ['orderBy' => 'date', 'orderDirection' => 'desc'])) }}">most recent</a>
            </button>
            <button>
                <a href="{{ route('search', array_merge(request()->query(), ['orderBy' => 'date', 'orderDirection' => 'asc'])) }}">oldest</a>
            </button>
        </div>

    </div>

    <hr>

    @if($totalResults == 1)
    <div><b> {{$totalResults}}</b> result:</div>
    @else
    <div><b> {{$totalResults}}</b> results:</div>
    @endif

    <ul class="questions_results_container">
        @forelse ($questions as $question)
         @if($question->content_is_visible)
            <li class="question_card">

                <div class="question_user_container">
                    <a href=""> <!-- route('member.show', $question->author) -->
                        <div class="question_user_photo">
                            <img src="{{ Storage::url($question->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
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
                    <p>{{ $question->createdAt }}</p>   
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
            @endif
        @empty
        <div class="no_result_message">
            <p>No results found. Please check back later.</p>
        </div>
        
        @endforelse
    </ul>

</div>

@endsection
