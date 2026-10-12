@extends('reports.layout')
@section('title', 'Directorate Report')
@section('content')
    @include('reports.header', [
        'url' => 'directorates.index',
        'back_name' => 'Back to Directorates',
        'header' => 'NCAA Directorates',
    ])
    <thead>
        <tr>
            <th>Name</th>
            <th>Function</th>
        </tr>
    </thead>
    <tbody id="itemTable">
        @forelse ($directorates as $directorate)
            <tr>
                <td>{{ $directorate->name }}</td>
                <td>{{ Str::limit($directorate->function, 30, '....') }}</td>
            </tr>
        @empty
            <tr colspan="3">
                No directorate registered!!!
            </tr>
        @endforelse
    </tbody>
@endsection
