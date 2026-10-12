@extends('reports.layout')
@section('title', 'Print Departments Report')
@section('content')
@include('reports.header', ['url' => 'departments.index', 'back_name' => 'Back to Departments', 'header' => 'NCAA Departments'])
    <thead>
        <tr>
            <th>Directorate</th>
            <th>Dept. Name</th>
            <th>Function</th>
        </tr>
    </thead>
    <tbody id="itemTable">
        @forelse ($departments as $department)
            <tr>
                <td>{{ $department->directorate?->name }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ Str::limit($department->function, 30, '....') }}</td>
            </tr>
        @empty
            <tr colspan="3">
                No directorate registered!!!
            </tr>
        @endforelse
    </tbody>
@endsection
