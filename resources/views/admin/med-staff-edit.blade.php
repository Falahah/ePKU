@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card shadow-sm" style="min-height: 70vh;">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <h5>Edit Medical Staff Details</h5>
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

                <form method="POST" action="{{ route('admin.updateMedStaff', ['medStaffId' => $medStaff->msID]) }}" onsubmit="return confirm('Are you sure you want to update this user?')">
                    @csrf
                    @method('PUT')
                    <!-- Add input fields for editing medical staff details -->
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name', $medStaff->name) }}">
                                <label for="name">Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male" {{ old('gender', $medStaff->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $medStaff->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email', $medStaff->email) }}">
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number', $medStaff->phone_number) }}">
                                <label for="phone_number">Phone Number</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="position" name="position" placeholder="Enter position" value="{{ old('position', $medStaff->position) }}">
                                <label for="position">Position</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="department" name="department_id">
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}" {{ old('department_id', $medStaff->department_id) == $department->department_id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <label for="department">Department</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="unit" name="unit_id">
                                    <option value="">Select Unit</option>
                                    <option value="">No Associated Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->unit_id }}" {{ old('unit_id', $medStaff->unit_id) == $unit->unit_id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <label for="unit">Unit</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="active" name="active">
                                    <option value="1" {{ $medStaff->active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $medStaff->active == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <label for="active">Status</label>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.medStaffDetails', ['medStaffId' => $medStaff->msID]) }}" class="btn btn-secondary" onclick="return confirmCancellation()">
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
        return confirm('Are you sure you want to cancel updating this staff member?');
    }

    // Function to set the active tab based on the current URL
    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/admin/medStaff/')) {
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
