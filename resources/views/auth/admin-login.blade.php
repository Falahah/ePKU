@extends('layouts.admin')

@section('content')
<div class="container login-page mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm fixed-width">
                <div class="card-header" style="background-color: #036EB8; color: white;">
                    <h4 class="text-center">{{ __('Admin Login') }}</h4>
                </div>

                <div class="card-body p-4">
                @if(session('status'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
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
@endsection
<style>
    body {
        background-image: url('{{ asset('img/bg_epku.png') }}'), linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5));
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-color: #d8dff1;
    }
    .fixed-width {
    width: 1200px; /* Adjust the width as needed */
    }


</style>
