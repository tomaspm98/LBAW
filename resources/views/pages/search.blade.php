@extends('layouts.app')

@section('content')

<div class="container">

    <form action="{{ route('search') }}" method="get">
        
        <select class="form-select" name="tag">
            <option value="default">Select a tag</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->tag_name }}" {{ $selectedTag && ($selectedTag->tag_id == $tag->tag_id) ? 'selected' : '' }}>
                    {{ $tag->tag_name }}
                </option>
            @endforeach
        </select>


        <input type="hidden" name="search" value="{{ request('search') }}">
        <button class="filtering-submit-button" type="submit">Apply Filters</button> 
    </form>

    <ul>
        @forelse ($questions as $question)
            <li class="question_card">
                
                <!-- <small>Asked by {{ $question->author->username ?? 'Unknown' }}</small> -->

                <div class="question_user_container">
                    <a href=""> <!-- route('member.show', $question->author) -->
                        <div class="question_user_photo">
                            <img src="" alt="Profile Photo">
                        </div>
                    </a>
                    <p><b>{{ $question->author->username ?? 'Unknown' }}</b></p>
                </div>
                
                <div class="top_questions_tittle">
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
        <p>No results found. Please check back later</p>
        @endforelse
    </ul>









</div>

@endsection
