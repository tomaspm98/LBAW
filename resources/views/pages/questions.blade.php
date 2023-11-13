{{-- resources/views/pages/questions.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Questions List</h1>
        <ul>
            @foreach ($questions as $question)
                <li>
                    <a href="{{ route('questions.show', $question->question_id) }}">{{ $question->question_title }}</a>
                    - <small>Asked by {{ $question->author->username ?? 'Unknown' }}</small>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
