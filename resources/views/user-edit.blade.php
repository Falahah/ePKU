@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card shadow-sm" style="min-height: 70vh;">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Edit User Details</span>
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>

            <div class="card-body p-4">
            <h2 class="text-center text-primary"><strong>{{ $user->username }}'s Details</strong></h2><br>                           <div class="row">

                @if ($errors->any())
                    <!-- Display validation errors -->
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <!-- Display success message -->
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('user.update', ['userId' => $user->userID]) }}" onsubmit="return confirm('Are you sure you want to update this user?')">
                    @csrf
                    @method('PUT')
                    <div class="col-md-8 mx-auto">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <!-- Left column fields -->
                            <div class="form-floating mb-3">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="{{ old('name', $user->name) }}">
                                <label for="name">Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" value="{{ old('username', $user->username) }}">
                                <label for="username">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="IC" id="IC" class="form-control" placeholder="Enter IC" value="{{ old('IC', $user->IC) }}"required oninput="updateDOB(this.value)">
                                <label for="IC">IC</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="Enter date of birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                                <label for="date_of_birth">Date of Birth</label>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <!-- Right column fields -->
                            <div class="form-floating mb-3">
                                <select name="gender" id="gender" class="form-select">
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" value="{{ old('email', $user->email) }}">
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" value="{{ old('phone_number', $user->phone_number) }}">
                                <label for="phone_number">Phone Number</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select name="role" id="role" class="form-select">
                                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <label for="role">Role</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('user.details', ['userId' => $user->userID]) }}" class="btn btn-secondary" onclick="return confirmCancellation()">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>         
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to handle the cancellation confirmation
    function confirmCancellation() {
        return confirm('Are you sure you want to cancel updating this user?');
    }

    // Function to set the active tab based on the current URL
    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/edit/')) {
            $('.nav-link[data-tab="manageUsers"]').addClass('active');
        } else {
            $('.nav-link').each(function () {
                var link = $(this).attr('href');
                if (currentPath === link) {
                    $(this).addClass('active');
                }
            });
        }

        // Call the updateDOB function on page load
        var icValue = $('#IC').val();
        updateDOB(icValue);
    });

    // Function to update password and date of birth based on IC
    function updateDOB(ic) {
        // Extract YYMMDD from the IC and set the date of birth field
        var yy = ic.substring(0, 2);
        var mm = ic.substring(2, 4);
        var dd = ic.substring(4, 6);
        var yyyy = new Date().getFullYear();

        // Adjust the year based on the first two digits of the IC
        if (parseInt(yy) > parseInt(yyyy.toString().substring(2))) {
            // 1900s
            yyyy = '19' + yy;
        } else {
            // 2000s
            yyyy = '20' + yy;
        }

        var dateOfBirth = yyyy + '-' + mm + '-' + dd;

        document.getElementById('date_of_birth').value = dateOfBirth;
    }
</script>

@endsection
