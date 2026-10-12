@extends('reports.layout')
@section('title', 'Print Firms Report')
@section('content')
    @include('reports.header', [
        'url' => 'Firms.index',
        'back_name' => 'Back to Firms',
        'header' => 'NCAA Firms',
    ])
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            @forelse ($contractors as $contractor)
                <tr>
                    <td>{{ $contractor->user?->name }}</td>
                    <td>{{ $contractor->phone }}</td>
                    <td>{{ $contractor->user?->email }}</td>
                    <td>{{ $contractor->firm_type?->name }}</td>
                </tr>
            @empty
                <tr colspan="3">
                    No firms registered!!!
                </tr>
            @endforelse
        </tbody>
@endsection
