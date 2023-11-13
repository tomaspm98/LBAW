@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($question->tag)
            <strong>Tag:</strong> {{ $question->tag->tag_name }}
        @else
            <strong>Tag:</strong> Not specified
        @endif
        <h1>{{ $question->question_title }}</h1>
        <h2>{{ $question->content_text }}</h2>
        <div>
            <strong>Author:</strong> {{ $question->author->username }}
        </div>
        <div>
            <form action="{{ route('answers.create', $question->question_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to submit this answer?')">
                @csrf
                <div class="form-group">
                    <label for="content_text">Post Answer:</label>
                    <textarea class="form-control" id="content_text" name="content_text" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Answer</button>
            </form>
            <br><h3>{{ $question->answer_count }} Answers: </h3>
            @foreach ($question->answers as $answer)
                <div>
                    <p>{{$answer->author->username }} :</p>  
                    <p>{{$answer->content_text }}</p> 
                </div>
            @endforeach    
        </div>

        {{-- Delete Button --}}
        <form method="POST" action="{{ route('questions.delete', $question->question_id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this question?')">Delete Question</button>
        </form>
    </div>
@endsection
