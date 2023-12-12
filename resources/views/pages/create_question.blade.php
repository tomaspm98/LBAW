{{-- resources/views/pages/create_question.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Create a New Question</h1>
    <form action="{{ route('questions.create.post') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label for="question_title" class="form-label">Question Title</label>
            <input type="text" class="form-control" id="question_title" name="question_title" required>
        </div>

        <div class="mb-3">
            <label for="content_text" class="form-label">Question Content</label>
            <textarea class="form-control fixed-height" style="min-height:47vh;" id="content_text" name="content_text" required></textarea>
        </div>

        <div class="mb-3">
            <label for="question_tag" class="form-label">Select a Tag</label>
            <select class="form-select" id="question_tag" name="question_tag" required>
                @foreach($tags as $tag)
                    <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to create this question?')">Create Question</button>
    </form>
</div>

@endsection
