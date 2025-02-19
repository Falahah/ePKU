@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card" style="min-height: 70vh;">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Manage Medical Staff</span>
                @include('partials.tabs')
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('admin.addMedStaffForm') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Medical Staff
                </a>
            </div>

            <div class="card-body">
                @if (session('message'))
                <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif                
            <ul class="nav nav-tabs" id="medStaffTabs" role="tablist">
                @foreach($departments as $department)
                <li class="nav-item">
                    <a class="nav-link{{ $loop->first ? ' active' : '' }}" id="{{ $department->name }}-tab" data-toggle="tab" href="#{{ $department->name }}" role="tab" aria-controls="{{ $department->name }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $department->name }}</a>
                </li>
                @endforeach
            </ul>
                <div class="tab-content" id="medStaffTabsContent">
                    @foreach($departments as $department)
                    <div class="tab-pane fade{{ $loop->first ? ' show active' : '' }}" id="{{ $department->name }}" role="tabpanel" aria-labelledby="{{ $department->name }}-tab">
                        <br>
                        <div class="mb-3">
                            <input type="text" id="{{ $department->name }}UserSearch" class="form-control" placeholder="Search {{ $department->name }} medical staff...">
                        </div>
                        <table class="table table-striped" id="{{ $department->name }}MedicalStaffTable">
                            <thead>
                                <tr>
                                    <th class="text-center">MSID</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Gender</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Phone Number</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach ($department->medicalStaff as $medStaff)
                                        <tr>
                                            <td class="text-center">{{ $medStaff->msID }}</td>
                                            <td class="text-center">{{ $medStaff->name }}</td>
                                            <td class="text-center">{{ $medStaff->gender }}</td>
                                            <td class="text-center">{{ $medStaff->email }}</td>
                                            <td class="text-center">{{ $medStaff->phone_number }}</td>
                                            <td class="text-center">{{ $medStaff->position }}</td>
                                            <td class="text-center">
                                                @if($medStaff->unit)
                                                    {{ $medStaff->unit->name }}
                                                @else
                                                    <em>No Associated Units</em>
                                                @endif
                                            </td>
                                            <td>
                                            <span id="medStaffStatus"> 
                                                            @if($medStaff->active)
                                                                <span class="text-success">Active</span>
                                                            @else
                                                                <span class="text-danger">Inactive</span>
                                                            @endif
                                                        </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.medStaffDetails', ['medStaffId' => $medStaff->msID]) }}" class="btn btn-info">
                                                    <i class="fas fa-info"></i>
                                                </a>
                                                <a href="{{ route('admin.editMedStaff', ['medStaffId' => $medStaff->msID]) }}" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="post" action="{{ route('admin.deleteMedStaff', ['medStaffId' => $medStaff->msID]) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this staff member?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('input[id$="UserSearch"]').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            var tableId = $(this).attr('id').replace('UserSearch', 'MedicalStaffTable');
            var $tableRows = $('#' + tableId + ' tbody tr');

            $tableRows.show();
            $tableRows.filter(function() {
                return $(this).text().toLowerCase().indexOf(searchText) === -1;
            }).hide();
        });
    });
</script>
@endsection
