@extends('errors.custom')

@section('title', 'Page not found')

@section('content')
    <div class="container text-center my-4">
        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo" />
        <h5 class="mt-3">Nigerian Civil Aviation Authority HQ, Abuja</h5>
    </div>

    <h1 class="error-title">404</h1>
    <h4 class="mb-3">Page Not Found</h4>
    <p>This page you are looking for could not be found.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go to Login</a>
@endsection
