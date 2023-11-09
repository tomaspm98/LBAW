{{-- resources/views/questions/edit.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Question</h1>
        <form action="{{ route('questions.update', $question->question_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="question_title">Title</label>
                <input type="text" class="form-control" id="question_title" name="question_title" value="{{ $question->question_title }}" required>
            </div>

            <div class="form-group">
                <label for="content_text">Description</label>
                <textarea class="form-control" id="content_text" name="content_text" required>{{ $question->content_text }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Question</button>
        </form>
    </div>
@endsection
