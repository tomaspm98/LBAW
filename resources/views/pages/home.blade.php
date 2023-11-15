@extends('layouts.app')

@section('content')





<div class="container">

    <h2>Top Questions</h2>
    listQuestions

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