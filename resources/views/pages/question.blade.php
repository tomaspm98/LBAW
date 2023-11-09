@extends('layouts.app')

@section('content')
    <div class="container">
        <strong>Tag:</strong> {{ $question->tag->tag_name }}
        <h1>{{ $question->question_title }}</h1>
        <h2>{{ $question->content_text }}</h2>
        <div>
            <strong>Author:</strong> {{ $question->author->username }}
        </div>
        <div>
            <br><h3>{{ $question->answer_count }} Answers: </h3>
            @foreach ($question->answers as $answer)
                <div>
                    <p>{{$answer->author->username }} :</p>  
                    <p>{{$answer->content_text }}</p> 
                </div>
            @endforeach    
        </div>
    </div>

@endsection
