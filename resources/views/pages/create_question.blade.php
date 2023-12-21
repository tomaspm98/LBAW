{{-- resources/views/pages/create_question.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="mb-4">Create a New Question</h1> 
    <div id="confirmationModal" class="custom-modal">
        <div class="modal-content-warn">
            <span class="close-modal">&times;</span>
            <p>Are you sure you want to create this question?</p>
            <button id="confirmYes" class="btn btn-success">Yes</button>
            <button id="confirmNo" class="btn btn-danger">No</button>
        </div>
    </div>
    <form id="createQuestionForm" action="{{ route('questions.create.post') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label for="question_title" class="form-label">Question Title</label>
            <input type="text" class="form-control" id="question_title" name="question_title" required  placeholder="Your Question">
        </div>

        <div class="mb-3">
            <label for="content_text" class="form-label">Question Content</label>
            <textarea class="form-control fixed-height" style="min-height:47vh;" id="content_text" name="content_text" required  placeholder="Write your question's information here. Try to be descriptive to get better help"></textarea>
        </div>

        <div class="mb-3">
            <label for="question_tag" class="form-label">Select a Tag</label>
            <select class="form-select" id="question_tag" name="question_tag" required>
                @foreach($tags as $tag)
                    <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" id="submitQuestion" style="display:none;" >Create Question</button>
    </form>
</div>

<script>
var questionTitle = document.getElementById("question_title");
    var contentText = document.getElementById("content_text");
    var questionTag = document.getElementById("question_tag");
    var createQuestionButton = document.getElementById("submitQuestion");

    // Add event listeners to the input fields to check for changes
    questionTitle.addEventListener("input", toggleCreateButton);
    contentText.addEventListener("input", toggleCreateButton);
    questionTag.addEventListener("change", toggleCreateButton);

    // Function to toggle the visibility of the Create Question button
    function toggleCreateButton() {
        if (questionTitle.value.trim() !== "" && contentText.value.trim() !== "" && questionTag.value !== "") {
            createQuestionButton.style.display = "block"; // Show the button
        } else {
            createQuestionButton.style.display = "none"; // Hide the button
        }
    }
    // Get the modal
    var modal = document.getElementById('confirmationModal');

    // Get the button that opens the modal
    var btn = document.getElementById("submitQuestion");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-modal")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function(event) {
        event.preventDefault();
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Get the Yes and No buttons
    var confirmYes = document.getElementById("confirmYes");
    var confirmNo = document.getElementById("confirmNo");

    // If Yes is clicked, submit the form
    confirmYes.onclick = function() {
    document.getElementById('createQuestionForm').submit();
};

    // If No is clicked, close the modal
    confirmNo.onclick = function() {
        modal.style.display = "none";
    }
</script>

@endsection



