@extends('layouts.admin')

@section('content')>
<div class="row justify-content-center">
    <div>
        <div class="card shadow">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>User Details</span>
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>

            <div class="card-body">
                {{-- Display success message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="col-md-8 mx-auto"><br>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.manageUsers') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="text-center text-primary"><strong>{{ $user->username }}'s Details</strong></h2>
                        <div class="d-flex">
                            <a href="{{ route('user.edit', ['userId' => $user->userID]) }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="post" action="{{ route('user.delete', ['userId' => $user->userID]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this staff member?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <br>
                    <dl class="row">
                        <div class="col-sm-12 bg-light p-4 rounded mb-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>User ID</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->userID }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Name</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->name }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Username</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->username }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>IC</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->IC }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Date of Birth</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ date('d/m/Y', strtotime($user->date_of_birth)) }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Gender</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->gender }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Email</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Phone Number</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->phone_number }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Role</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->role }}
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to set the active tab based on the current URL
    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/user-details/')) {
            $('.nav-link[data-tab="manageUsers"]').addClass('active');
        } else {
            $('.nav-link').each(function () {
                var link = $(this).attr('href');
                if (currentPath === link) {
                    $(this).addClass('active');
                }
            });
        }
    });
</script>
@endsection
