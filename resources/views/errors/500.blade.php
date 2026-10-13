@extends('errors.custom')

@section('title', 'Server Error')

@section('content')
    <div class="container text-center my-4">
        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo" />
        <h5 class="mt-3">Nigerian Civil Aviation Authority HQ, Abuja</h5>
    </div>

    <h1 class="error-title">500</h1>
    <h4 class="mb-3">Server Error</h4>
    <p>There's an issue on the server side with processing your request. Please contact the admin</p>

    @if (config('app.debug') && isset($exception))
        <div class="alert alert-danger text-start mt-4 p-3 overflow-auto" style="max-height: 250px; font-size: 0.85rem;">
            <strong>Exception:</strong> {{ $exception->getMessage() }}<br>
            <small class="text-muted">File: {{ $exception->getFile() }}:{{ $exception->getLine() }}</small>
        </div>
    @endif

    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Home</a>
@endsection

