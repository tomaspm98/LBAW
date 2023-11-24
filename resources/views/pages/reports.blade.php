@extends('layouts.app')

@section('content')
<h1>All Reports</h1>
@if($reports->isEmpty())
        <p>No reports found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Creator</th>
                    <th>Reason</th>
                    <th>Text</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
    <tr>
                <a href=""></a>
                <td>{{ $report->creator->username ?? 'unkown' }}</td>
                <td>{{ $report->report_reason }}</td>
                <td>{{ $report->report_text }}</td>
    </tr>
    
@endforeach

            </tbody>
        </table>
    @endif
@endsection
