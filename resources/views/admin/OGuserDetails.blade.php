@extends('layouts.admin')

@section('content')
<div class="container-fluid text-center">
    <h4>Welcome, {{ Auth::user()->username }}!</h4><br>
</div>

<div class="row justify-content-center">
    <div>
        <div class="card" style="min-height: 70vh;">
            <div class="card-header bg text-white">
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>

            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('User Details') }}</div>

                        <div class="card-body">
                            <!-- Display success message if any -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <dl class="row">
                                <dt class="col-sm-3">User ID</dt>
                                <dd class="col-sm-9" id="user_id">{{ $user->userID }}</dd>

                                <dt class="col-sm-3">Name</dt>
                                <dd class="col-sm-9"><span id="name">{{ $user->name }}</span></dd>

                                <dt class="col-sm-3">Username</dt>
                                <dd class="col-sm-9"><span id="username">{{ $user->username }}</span></dd>

                                <dt class="col-sm-3">IC</dt>
                                <dd class="col-sm-9" id="icContainer">
                                    <span id="ic">{{ $user->IC }}</span>
                                    <input type="text" class="form-control" id="ic_input" value="{{ $user->IC }}" style="display: none;" oninput="updateDOB(this.value)">
                                </dd>

                                <dt class="col-sm-3">Date of Birth</dt>
                                <dd class="col-sm-9">
                                    <span id="dob">{{ date('Y-m-d', strtotime($user->date_of_birth)) }}</span>
                                    <input type="date" class="form-control" id="dob_input" value="{{ date('Y-m-d', strtotime($user->date_of_birth)) }}" style="display: none;">
                                </dd>
                                
                                <dt class="col-sm-3">Gender</dt>
                                <dd class="col-sm-9"><span id="gender">{{ $user->gender }}</span></dd>

                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9"><span id="email">{{ $user->email }}</span></dd>

                                <dt class="col-sm-3">Phone Number</dt>
                                <dd class="col-sm-9"><span id="phone_number">{{ $user->phone_number }}</span></dd>

                                <dt class="col-sm-3">Role</dt>
                                <dd class="col-sm-9"><span id="role">{{ $user->role }}</span></dd>
                            </dl>
                            
                            <!-- Edit button -->
                            <div class="form-group" style="display: flex;">
                                <button id="editBtn" class="btn btn-primary" style="margin-right: 10px;">Edit</button>
                                <a href="{{ route('admin.viewAllUsers') }}" class="btn btn-secondary">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editBtn').addEventListener('click', function() {
        // Toggle visibility of IC elements
        var icContainer = document.getElementById('icContainer');
        var icSpan = document.getElementById('ic');
        var icInput = document.getElementById('ic_input');

        if (icSpan.style.display !== 'none') {
            icSpan.style.display = 'none';
            icInput.style.display = 'block';
        } else {
            icSpan.style.display = 'block';
            icInput.style.display = 'none';
        }

        var fields = ['name', 'username', 'ic', 'dob', 'gender', 'email', 'phone_number', 'role'];
        fields.forEach(function(field) {
            var element = document.getElementById(field);
            var value = element.innerText;
            var input;

            if (field === 'gender' || field === 'role') {
                input = document.createElement('select');
                input.setAttribute('class', 'form-control');
                input.setAttribute('id', field + '_input');

                // Add options to select
                var options = ['male', 'female']; // Gender options
                if (field === 'role') {
                    options = ['admin', 'student', 'staff']; // Role options
                }
                options.forEach(function(option) {
                    var optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.text = option.charAt(0).toUpperCase() + option.slice(1); // Capitalize first letter
                    if (option === value.toLowerCase()) {
                        optionElement.selected = true;
                    }
                    input.appendChild(optionElement);
                });
            } else if (field === 'dob') {
                input = document.createElement('input');
                input.setAttribute('type', 'date');
                input.setAttribute('class', 'form-control');
                input.setAttribute('id', field + '_input');
                input.setAttribute('value', value);
            } else {
                input = document.createElement('input');
                input.setAttribute('type', 'text');
                input.setAttribute('class', 'form-control');
                input.setAttribute('value', value);
                input.setAttribute('id', field + '_input');
            }

            element.innerHTML = '';
            element.appendChild(input);
        });

        // Change button text and functionality
        document.getElementById('editBtn').innerText = 'Save';
        document.getElementById('editBtn').removeEventListener('click', this);
        document.getElementById('editBtn').addEventListener('click', function() {
            // Perform save functionality here
            fields.forEach(function(field) {
                var input = document.getElementById(field + '_input');
                var value = input.value;
                document.getElementById(field).innerText = value;
            });

            // Change button text back to Edit
            document.getElementById('editBtn').innerText = 'Edit';

            // Remove input fields
            fields.forEach(function(field) {
                var element = document.getElementById(field);
                element.removeChild(document.getElementById(field + '_input'));
            });
        });
    });

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

    // Set the value of the date picker input field
    document.getElementById('dob_input').value = dateOfBirth;
}

</script>

@endsection
