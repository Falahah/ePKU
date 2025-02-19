@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Add New User</span>
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>
            <div class="card-body p-4">
                {{-- Display validation errors --}}
                @if ($errors->any())
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

                <!-- Add your user creation form here -->
                <form method="post" action="{{ route('admin.addUser') }}" onsubmit="return confirmSubmit()">
                    <h2 class="text-center text-primary"><strong>Add New User</strong></h2><br>
                    @csrf
                    <!-- Form inputs -->
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name') }}" required>
                                <label for="name">Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="{{ old('username') }}" required>
                                <label for="username">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="IC" name="IC" placeholder="Enter IC" value="{{ old('IC') }}" required oninput="updatePasswordAndDOB(this.value)">
                                <label for="IC">IC</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                <label for="password">Password</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Select date of birth" value="{{ old('date_of_birth') }}" required>
                                <label for="date_of_birth">Date of Birth</label>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number') }}" required>
                                <label for="phone_number">Phone Number</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <!-- Add other role options as needed -->
                                </select>
                                <label for="role">Role</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add User
                        </button>
                        <a href="{{ route('admin.manageUsers') }}" class="btn btn-secondary" onclick="return confirmCancel()">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to set the active tab based on the current URL
    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/admin/add-user')) {
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
    function confirmSubmit() {
        return confirm('Are you sure you want to submit the form?');
    }
    
    function confirmCancel() {
        return confirm('Are you sure you want to cancel?');
    }

    function updatePasswordAndDOB(ic) {
        // Set the value of the password field to the IC value
        document.getElementById('password').value = ic;

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
