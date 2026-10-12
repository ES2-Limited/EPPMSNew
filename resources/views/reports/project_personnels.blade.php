@extends('reports.layout')
@section('title', 'Project Personnels Report')
@section('content')
    @include('reports.header', [
        'back_name' => 'Back to Project Personnels',
        'header' => 'NCAA Project Personnels',
    ])
        <thead>
            <tr>
                <th>Project Creator</th>
                <th>Project Personnel</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            @forelse ($project_personnels as $personnel)
                <tr>
                    <td>{{ $personnel->project?->creator?->name }}</td>
                    <td>{{ $personnel->personnel?->user?->first_name .' '. $personnel->personnel?->user?->last_name }}</td>
                </tr>
            @empty
                <tr colspan="3">
                    No project personnels registered!!!
                </tr>
            @endforelse
        </tbody>
@endsection
