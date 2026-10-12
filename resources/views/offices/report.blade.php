@extends('reports.layout')
@section('title', 'Print Offices Report')
@section('content')
    @include('reports.header', [
        'url' => 'offices.index',
        'back_name' => 'Back to Offices',
        'header' => 'NCAA Offices',
    ])
    <thead>
        <tr>
            <th>Name</th>
            <th>Office Type</th>
            <th>State</th>
        </tr>
    </thead>
    <tbody id="itemTable">
        @forelse ($offices as $office)
            <tr>
                <td>{{ $office->name }}</td>
                <td>{{ $office->office_type?->name }}</td>
                <td>{{ $office->lga?->state?->name }}</td>
            </tr>
        @empty
            <tr colspan="3">
                No offices registered!!!
            </tr>
        @endforelse
    </tbody>
@endsection
