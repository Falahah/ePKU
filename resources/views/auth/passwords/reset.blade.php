@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-primary">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            <label for="email">{{ __('Email Address') }}</label>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password" required autocomplete="new-password">
                            <label for="password">{{ __('Password') }}</label>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Confirm password" required autocomplete="new-password">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-redo"></i>
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
