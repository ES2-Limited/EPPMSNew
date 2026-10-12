@extends('reports.layout')
@section('title', 'Print Contractor Personnels Report')
@section('content')
    @include('reports.header', [
        'url' => 'contractor-personnels.index',
        'back_name' => 'Back to Contractor Personnels',
        'header' => 'NCAA Contractor Personnels',
    ])
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Contractor Name</th>
        </tr>
    </thead>
    <tbody id="itemTable">
        @forelse ($personnels as $personnel)
            <tr>
                <td>{{ $personnel->user?->first_name . ' ' . $personnel->user?->last_name }}</td>
                <td>{{ $personnel->phone }}</td>
                <td>{{ $personnel->user?->email }}</td>
                <td>{{ $personnel->contractor?->name }}</td>
            </tr>
        @empty
            <tr colspan="3">
                No personnels registered!!!
            </tr>
        @endforelse
    </tbody>
@endsection
