@extends('reports.layout')
@section('title', 'Print Personnel Report')
@section('content')
    @include('reports.header', [
        'url' => 'personnels.index',
        'back_name' => 'Back to Personnels',
        'header' => 'NCAA Personnels',
    ])
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Directorate Name</th>
                <th>Department Name</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            @forelse ($personnels as $personnel)
                <tr>
                    <td>{{ $personnel->user?->first_name . ' ' . $personnel->user?->last_name }}</td>
                    <td>{{ $personnel->department?->directorate?->name }}</td>
                    <td>{{ $personnel->department?->name }}</td>
                </tr>
            @empty
                <tr colspan="3">
                    No personnels registered!!!
                </tr>
            @endforelse
        </tbody>
@endsection
