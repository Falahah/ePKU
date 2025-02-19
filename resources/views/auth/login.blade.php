@extends('layouts.app')

@section('content')
<div class="container login-page mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-orange text-white text-center">
                    <h4>{{ __('Login') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('status'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="alert alert-info text-center">
                        <strong>First-Time Login</strong><br>
                        📌 Username: Matric Number / Staff ID<br>
                        📌 Password: Your IC
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Login') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-image: url('{{ asset('img/bg_epku.png') }}'), linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5));
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-color: #d8dff1;
    }

    .bg-orange {
        background-color: #FFA500;
    }

    .card {
        border: none;
    }

    .btn-primary {
        background-color: #036EB8;
        border: none;
    }

    .btn-primary:hover {
        background-color: #024F7C;
    }

    .text-decoration-none {
        color: #036EB8;
    }

    .text-decoration-none:hover {
        color: #024F7C;
    }
</style>
@endsection
