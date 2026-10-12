@extends('reports.layout')
@section('title', 'Print Units Report')
@section('content')
    @include('reports.header', [
        'url' => 'units.index',
        'back_name' => 'Back to Units',
        'header' => 'NCAA Units',
    ])
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Unit Name</th>
                <th>Function</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            @forelse ($units as $unit)
                <tr>
                    <td>{{ $unit->department?->name }}</td>
                    <td>{{ $unit->name }}</td>
                    <td>{{ Str::limit($unit->function, 30, '....') }}</td>
                </tr>
            @empty
                <tr colspan="3">
                    No Units registered!!!
                </tr>
            @endforelse
        </tbody>
@endsection
