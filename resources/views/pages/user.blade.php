@extends('layouts.app')

@section('content')
    <div class="container">
        <h2><strong>{{ $member->username }} profile page</strong></h2>
    
        <div>
            <br><h3>Email: {{ $member->user_email }} </h3>
            <br><h3>picture: {{ $member->picture }} </h3>
            <br><h3>score: {{ $member->user_score }} </h3>

            <br><h3>{{$member->questions_count}}  Questions: </h3>
            @foreach ($member->questions as $question)
                <div>
                    <p>{{$question->author->username }} :</p>  
                    <p>{{$question->content_text }}</p> 
                </div>
            
             @endforeach 

             <br><h3>{{$member->comments_count}}  Comments: </h3>
            @foreach ($member->comments as $comment)
                <div>
                    <p>{{$comment->author->username }} :</p>  
                    <p>{{$comment->content_text }}</p> 
                </div>
            
             @endforeach 

             <br><h3>{{$member->answer_count}}  Answers: </h3>
            @foreach ($member->answers as $answer)
                <div>
                    <p>{{$answer->author->username }} :</p>  
                    <p>{{$answer->content_text }}</p> 
                </div>
            
             @endforeach 

        </div>
    </div>

@endsection
