@extends('layouts.app')

@section('content')
@if($comment->content_is_visible) <!-- Assuming you have a 'content_is_visible' attribute for comments -->
    <div class="container">
        <h1>Edit Comment</h1>
        <form action="{{ route('comments.update', ['question_id' => $comment->getQuestion(), 'answer_id' => $comment->answer_id, 'comment_id' => $comment->comment_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this comment?')">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="comment_content_text">Comment</label>
                <textarea class="form-control" id="comment_content_text" name="content_text" required>{{ $comment->content_text }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Comment</button>
        </form>
            <form action="{{ route('comments.delete', ['question_id' => $comment->getQuestion(), 'answer_id' => $comment->answer_id, 'comment_id' => $comment->comment_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Comment</button>
            </form>
    </div>
@else 
    <?php abort(404); ?>
@endif
@endsection
