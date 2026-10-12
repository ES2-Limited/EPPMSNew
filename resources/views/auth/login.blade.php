@extends('layouts.master')

@section('content')
    <div id="main-wrapper">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
            <div class="position-relative z-index-5">
                <div class="row gx-0">
                    <div class="col-lg-6 col-xl-5 col-xxl-4">
                        <div class="min-vh-100 bg-body row justify-content-center align-items-center p-5">
                            <div class="col-12 auth-card">
                                <a href="/" class="text-nowrap logo-img d-block w-100">
                                    <img src="{{ asset('/assets/images/logos/ncaa-icon.png') }} " class="dark-logo"
                                        alt="Logo-Dark" />
                                </a>
                                <h2 class="mb-2 mt-4 fs-7 fw-bolder">Log In to NCAA’s Management System</h2>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                                        <input type="email" class="form-control" id="email"
                                            aria-describedby="emailHelp" placeholder="Email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus />
                                        <div class="col-md-6">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" id="password" placeholder="Password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password" />
                                        <div class="col-md-6">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                        @if (Route::has('password.request'))
                                            <a class="text-primary fw-medium" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
                                        {{ __('Sign In') }}
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
