@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Add New Medical Staff</span>
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

                <!-- Add your medical staff creation form here -->
                <form method="post" action="{{ route('addMedStaff') }}" onsubmit="return confirmSubmit()">
                    <h2 class="text-center text-primary"><strong>Add New Medical Staff</strong></h2><br>
                    @csrf
                    <!-- Form inputs -->
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name') }}" required>
                                <label for="name">Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="position" name="position" placeholder="Enter position" value="{{ old('position') }}" required>
                                <label for="position">Position</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number') }}" required>
                                <label for="phone_number">Phone Number</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                                <label for="email">Email</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>  
                            <div class="form-floating mb-3">
                                <select class="form-control" id="department_id" name="department_id" required>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->department_id }}" {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="department_id">Department</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-control" id="unit_id" name="unit_id">
                                    <option value="">No Associated Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->unit_id }}" {{ old('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="unit_id">Unit (Optional)</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Medical Staff
                        </button>
                        <a href="{{ route('admin.manageMedStaff') }}" class="btn btn-secondary" onclick="return confirmCancel()">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmSubmit() {
        return confirm('Are you sure you want to submit the form?');
    }

    function confirmCancel() {
        return confirm('Are you sure you want to cancel?');
    }

    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/admin/add-medical-staff')) {
            $('.nav-link[data-tab="manageMedStaff"]').addClass('active');
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
