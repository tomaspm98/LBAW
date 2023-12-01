

@extends('layouts.app')

@section('content')

<div class="container">

    <form class="search_form row align-items-end" action="{{ route('search') }}" method="GET">

        <div class="col-md-4">
            <label for="search" class="form-label">Search:</label>
            <input type="text" class="form-control" name="search" value="{{ Session::get('searchTerm') }}" placeholder="Search...">
        </div>

        <div class="col-md-4 mt-2 mt-md-0">
            <label for="tag" class="form-label">Filter by Tag name:</label>
            <select class="form-select" name="tag">
                <option value="all">All tags</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->tag_name }}" {{ $selectedTag == $tag->tag_name ? 'selected' : '' }}>
                        {{ $tag->tag_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mt-2 mt-md-0">
            <label class="form-label d-block">Sort by:</label>
            <div class="btn-group" role="group">
                <a href="{{ route('search', array_merge(request()->query(), ['orderBy' => 'date', 'orderDirection' => 'desc'])) }}" class="btn btn-secondary">Most Recent</a>
                <a href="{{ route('search', array_merge(request()->query(), ['orderBy' => 'date', 'orderDirection' => 'asc'])) }}" class="btn btn-secondary">Oldest</a>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary mt-3">Search</button>
        </div>
    </form>

    <hr>

    <div class="mb-3">
        <b>{{ $totalResults }}</b> {{ $totalResults == 1 ? 'result:' : 'results:' }}
    </div>

    <ul class="questions_results_container list-unstyled">
        @forelse ($questions as $question)
        @if($question->content_is_visible)

        <li class="card p-2 question_card">
            <div class="row no-gutters">
                <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                    <a href="{{ route('member.show', $question->author) }}">
                        <div class="question_user_photo">
                            <img src="{{ Storage::url($question->author->picture) ?? asset('storage/pictures/default/profile_picture.png') }}" alt="Profile Photo">
                        </div>
                        <div class="mt-2">
                            <p class="mb-0"><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                        </div>
                    </a>
                </div>

                <div class="col-md-7">
                    <div class="card-body">
                        <b>Tag:</b> {{ $question->tag->tag_name }}
                        <div class="top_questions_title">
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('questions.show', $question->question_id) }}"  > 
                                <h3>{{ Str::limit($question->question_title, 50) }}</h3>
                            </a>     
                        </div>
                        <p class="question-text">{{ Str::limit($question->content_text, 70) }}   
                            @if(strlen($question->content_text > 50))
                            <a class="more_details" href="{{ route('questions.show', $question->question_id) }}"> more details</a>  
                            @endif
                        </p>   
                        <p>{{ $question->createdAt }}</p>
                    </div>
                </div>
                <div class="col-md-3 d-flex justify-content-around align-items-center text-center">
                    <div class="top_questions_n_answers">
                        @if($question->answer_count !== 1)
                        <p>{{ $question->answer_count }} answers</p>
                        @else
                        <p>{{ $question->answer_count }} answer</p>
                        @endif
                    </div>
                    <div class="top_questions_votes">
                        <p>{{ $question->vote_count }} votes</p> 
                    </div>
                </div>
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
