@extends('layouts.master')

@section('content')
    <div id="main-wrapper">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
            <div class="position-relative z-index-5">
                <div class="row gx-0">
                    <div class="col-lg-6 col-xl-5 col-xxl-4">
                        <div class="min-vh-100 bg-body row justify-content-center align-items-center p-5">
                            <div class="col-12 auth-card">
                                <div class="mb-4">
                                    <a href="/" class="text-nowrap logo-img d-inline-block">
                                        <img src="{{ asset('/assets/images/logos/ncaa-icon.png') }}" class="dark-logo"
                                            alt="Logo-Dark" style="height: 50px; width: auto;" />
                                    </a>
                                </div>
                                <h3 class="mb-2 fs-6 fw-bold text-dark">Sign in to EPPMS</h3>
                                <p class="text-muted small mb-4">Enterprise Project & Portfolio Management System</p>

                                @if ($errors->any())
                                    <div class="alert alert-danger border-0 rounded-3 shadow-sm py-2">
                                        <ul class="mb-0 small ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold text-dark">{{ __('Email Address') }}</label>
                                        <input type="email" id="email"
                                            placeholder="name@ncaa.gov.ng"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label for="password" class="form-label fw-semibold text-dark mb-0">Password</label>
                                            @if (Route::has('password.request'))
                                                <a class="text-primary small text-decoration-none fw-medium" href="{{ route('password.request') }}">
                                                    {{ __('Forgot password?') }}
                                                </a>
                                            @endif
                                        </div>
                                        <input type="password" id="password" placeholder="••••••••"
                                            class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password" />
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2 fs-4 rounded-3 shadow-sm mt-3">
                                        {{ __('Sign In to Portal') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-xl-7 col-xxl-8 position-relative overflow-hidden bg-dark d-none d-lg-block">
                        <div class="circle-top"></div>
                        <div class="d-lg-flex align-items-center z-index-5 position-relative h-n80">
                            <div class="row justify-content-center w-100">
                                <div class="col-lg-6">
                                    <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Your Logo"
                                        class="img-fluid mb-4" style="max-width: 80%; height: auto;">
                                    <h2 class="text-white fs-10 mb-3 lh-base">
                                        Welcome to
                                        <br />
                                        EPPMS
                                    </h2>
                                    <span class="opacity-75 fs-4 text-white d-block mb-3">
                                        Electronic Project and Portfolio Management System
                                    </span>
                                    <a href="#" class="btn btn-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
