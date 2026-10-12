@extends('reports.layout')
@section('title', '`Roles Report')
@section('content')
    @include('reports.header', [
        'url' => 'roles.index',
        'back_name' => 'Back to Roles',
        'header' => 'NCAA Roles',
    ])
        <thead>
            <tr>
                <th>Name</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody id="itemTable">
            @forelse ($roles as $role)
                <tr>
                    <td>{{ ucfirst(str_replace(['_', ' '], ' ', $role->name)) }} </td>
                    <td> {{ $role->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr colspan="3">
                    No personnels registered!!!
                </tr>
            @endforelse
        </tbody>
@endsection
