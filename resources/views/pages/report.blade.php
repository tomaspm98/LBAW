@extends('layouts.app')

@section('content')
    <div class="container">

       <h2><strong>{{ $report->creator->username ?? 'unkown' }}</strong></h2> 
        <div>
            
       
        <br> <h3>Reason: {{ $report->report_reason }}</h3>
        <br> <h3>Text: {{ $report->report_text }}</h3>
        <br> Reported content: 
            @if($report->content_reported_question)
            <a href="{{ route('questions.show', $report->content_reported_question) }}">
                <h3>{{ $report->content_reported_question }}</h3>
            </a>
            
            @endif

            
        </div>
    </div>


@endsection