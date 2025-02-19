@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">{{ __('Change Password') }}</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="change-password-form" method="POST" action="{{ route('update-password') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                            <label for="current_password">{{ __('Current Password') }}</label>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required minlength="8">
                            <label for="new_password">{{ __('New Password') }}</label>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="confirm_password" type="password" class="form-control @error('confirm_password') is-invalid @enderror" name="confirm_password" required>
                            <label for="confirm_password">{{ __('Confirm Password') }}</label>
                            @error('confirm_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary" onclick="confirmPasswordChange()">                                 
                                <i class="fas fa-key"></i> Change Password
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelPasswordChange()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmPasswordChange() {
        if (confirm('Are you sure you want to change your password?')) {
            document.getElementById('change-password-form').submit();
        }
    }

    function cancelPasswordChange() {
        if (confirm('Are you sure you want to cancel password change?')) {
            window.location.href = "{{ route('profile') }}";
        }
    }
</script>

@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

@endsection
