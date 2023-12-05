@extends('layouts.app')

@section('content')

<div id="success-message" style="display: none">

    Tag created successfully!
</div>

    <section id="tags">
        <ul>
            <h1><strong>Tags Available:</strong></h1>
            @foreach ($tags as $tag)
                <li>{{$tag->tag_name}}</li>
            @endforeach
        </ul>

        <button type="button" id="showTagFormButton" class="btn btn-primary">
            Create New Tag
        </button>

        <form method="POST" action="{{ route('tags.create') }}" id="createTagForm" style="display: none;">
            @csrf
            <div class="mb-3">
                <label for="tagName">Tag Name:</label>
                <input type="text" id="tagName" name="tag_name" required>
                @error('tag_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tagDescription">Tag Description:</label>
                <textarea id="tagDescription" name="tag_description" rows="4" required></textarea>
                @error('tag_description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>
        
    </section>

    <script>
        document.getElementById('showTagFormButton').addEventListener('click', function() {
            document.getElementById('createTagForm').style.display = 'block';
        });

        const successMessage = document.getElementById('success-message');
        const createTagForm = document.getElementById('createTagForm');

        createTagForm.addEventListener('submit', function() {
            successMessage.style.display = 'block';

            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 2000);
        });
    </script>
@endsection
