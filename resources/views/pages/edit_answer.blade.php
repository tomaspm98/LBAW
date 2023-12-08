@extends('layouts.app')

@section('content')
@if($answer->content_is_visible)
    <div class="container">
        <h1>Edit Answer</h1>
        <form action="{{ route('answers.update', ['question_id' => $answer->question_id, 'answer_id' => $answer->answer_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this answer?')">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="content_text">Answer</label>
                <textarea class="form-control" id="content_text" name="content_text" required>{{ $answer->content_text }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Answer</button>
        </form>
    </div>
@else 
    <?php abort(404); ?>
@endif
@endsection
