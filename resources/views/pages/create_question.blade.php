{{-- resources/views/pages/create_question.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a New Question</h1>
        <form action="{{ route('questions.create.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="question_title">Question Title</label>
                <input type="text" class="form-control" placeholder="Question Title.." id="question_title" name="question_title" required>
            </div>

            <div class="form-group">
                <label for="content_text">Question Content</label>
                <textarea class="form-control" id="content_text" placeholder="Write your question.."name="content_text" required></textarea>
            </div>

            {{-- <div class="form-group">
                <label for="question_tag">Tag ID</label>
                <input type="number" class="form-control" id="question_tag" name="question_tag" required>
            </div> --}}

            @csrf
            <select name="question_tag">
                @foreach($tags as $tag)
                    <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to create this question?')">Create Question</button>
        </form>
    </div>
@endsection
