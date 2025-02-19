@extends('layouts.admin')

@section('content')
<style>
    #userTabs .nav-link {
        background-color: #e9ecef; 
        color: #036EB8; 
        border-color: #c3c3c3; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    #userTabs .nav-link.active {
        background-color: #036EB8;
        color: #fff; 
        font-weight: bold; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }
</style>
<div class="row justify-content-center">
    <div>
        <div class="card" style="min-height: 70vh;">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Manage Users</span>
                @include('partials.tabs')
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('admin.addUserForm') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif                
                <ul class="nav nav-tabs" id="userTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="admin-tab" data-bs-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="student-tab" data-bs-toggle="tab" href="#student" role="tab" aria-controls="student" aria-selected="false">Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="staff-tab" data-bs-toggle="tab" href="#staff" role="tab" aria-controls="staff" aria-selected="false">Staff</a>
                    </li>
                </ul>
                <div class="tab-content" id="userTabsContent">
                    <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                        <div class="mb-3">
                            <br><input type="text" id="adminUserSearch" class="form-control" placeholder="Search admin users...">
                        </div>
                        @include('partials.user-table', ['tab' => 'admin', 'users' => $adminUsers])
                    </div>

                    <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                        <div class="mb-3">
                            <br><input type="text" id="studentUserSearch" class="form-control" placeholder="Search student users...">
                        </div>
                        @include('partials.user-table', ['tab' => 'student', 'users' => $studentUsers])
                    </div>
                    
                    <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                        <div class="mb-3">
                            <br><input type="text" id="staffUserSearch" class="form-control" placeholder="Search staff users...">
                        </div>
                        @include('partials.user-table', ['tab' => 'staff', 'users' => $staffUsers])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeTab = localStorage.getItem('activeTab');
    const tabElement = activeTab ? document.querySelector(`#${activeTab}-tab`) : document.querySelector('#admin-tab');

    if (tabElement) {
        document.querySelectorAll('#userTabs .nav-link').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));

        tabElement.classList.add('active');
        document.querySelector(`#${tabElement.getAttribute('aria-controls')}`).classList.add('show', 'active');
    } else {
        document.querySelector('#admin-tab').classList.add('active');
        document.querySelector('#admin').classList.add('show', 'active');
    }

    const tabLinks = document.querySelectorAll('#userTabs .nav-link');
    tabLinks.forEach(tab => {
        tab.addEventListener('click', function () {
            localStorage.setItem('activeTab', this.getAttribute('aria-controls'));
            document.querySelectorAll('#userTabs .nav-link').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            this.classList.add('active');
            document.querySelector(`#${this.getAttribute('aria-controls')}`).classList.add('show', 'active');
        });
    });
});
</script>
@endsection
