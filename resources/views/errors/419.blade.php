@extends('errors.custom')

@section('title', 'Page Expired')

@section('content')
    <div class="container text-center my-4">
        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo" />
        <h5 class="mt-3">Nigerian Civil Aviation Authority HQ, Abuja</h5>
    </div>

    <h1 class="error-title">419</h1>
    <h4 class="mb-3">Page Expired</h4>
    <p>Your session has expired. Please login again to continue.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
@endsection
