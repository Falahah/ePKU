@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card shadow">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Medical Staff Details</span>
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>

            <div class="card-body">
                {{-- Display success message --}}
                @if (session('success'))
                    <!-- Display success message -->
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="col-md-8 mx-auto"><br>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.manageMedStaff') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="text-center text-primary"><strong>{{ $medStaff->name }}'s Details</strong></h2>
                        <div class="d-flex">
                            <a href="{{ route('admin.editMedStaff', ['medStaffId' => $medStaff->msID]) }}" class="btn btn-primary mx-2">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Add a form for deleting the staff member -->
                        <form method="post" action="{{ route('admin.deleteMedStaff', ['medStaffId' => $medStaff->msID]) }}" style="display:inline;">
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
                                <strong>Staff ID</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->msID }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Name</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Gender</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->gender }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Email</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->email }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Phone Number</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->phone_number }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Position</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->position }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Department</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->department->name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Unit</strong>
                            </div>
                            <div class="col-sm-8">
                                @if($medStaff->unit)
                                    {{ $medStaff->unit->name }}
                                @else
                                    <em>No Associated Unit</em>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Status</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $medStaff->active ? 'Active' : 'Inactive' }}
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
        if (currentPath.includes('/admin/medStaffDetails/')) {
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
