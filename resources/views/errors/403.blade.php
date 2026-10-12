@extends('errors.custom')

@section('title', 'Forbidden Access')

@section('content')
    <div class="container text-center my-4">
        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo" />
        <h5 class="mt-3">Nigerian Civil Aviation Authority HQ, Abuja</h5>
    </div>

    <h1 class="error-title">403</h1>
    <h4 class="mb-3">Unauthorized action</h4>
    <p>You don't have permission to access this page.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Home</a>
@endsection
