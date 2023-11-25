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
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
    <tr>
                
                <td>{{ $report->creator->username ?? 'unkown' }} </td>
                <td>{{ $report->report_reason }}</td>
                <td>{{ $report->report_text }}</td>
                <td><a href=" {{ route('report.view', $report->report_id)}}"> Go to this report </a></td>
    </tr>
    
@endforeach

            </tbody>
        </table>
    @endif
@endsection
