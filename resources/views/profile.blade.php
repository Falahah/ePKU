@extends('layouts.app')
@section('content')
<style>
    .card-header {
        background-color: #007bff;
        color: white;
        font-size: 1.25rem;
        position: relative;
    }
    .info-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    .info-tooltip {
        display: none;
        position: absolute;
        font-size: 1rem;
        top: 30px;
        right: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px;
        border-radius: 5px;
        width: 250px;
        z-index: 1;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .card {
        border-radius: 16px;
    }
    .card-body {
        padding: 2rem;
    }
    .form-floating {
        margin-bottom: 1rem;
    }
    .position-relative {
        position: relative;
    }

    .position-absolute {
        position: absolute;
    }

</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    {{ __('User Profile') }}
                    <i class="fas fa-info-circle info-icon"></i>
                    <div class="info-tooltip">
                        Other personal details are not editable since they are registered under UTHM.<br>You may only edit your phone number.
                    </div>
                </div>

                <div class="card-body">
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
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" value="{{ $user->name }}" readonly>
                                <label for="name">Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" value="{{ $user->username }}" readonly>
                                <label for="username">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="ic" value="{{ $user->IC }}" readonly>
                                <label for="ic">IC</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="dob" value="{{ $user->date_of_birth }}" readonly>
                                <label for="dob">Date of Birth</label>
                            </div>
                        </div>
                
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="gender" value="{{ $user->gender }}" readonly>
                                <label for="gender">Gender</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="role" value="{{ $user->role }}" readonly>
                                <label for="role">Status (Role)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="phone-number" value="{{ $user->phone_number }}" readonly>
                                <label for="phone-number">Phone Number</label>
                                <div class="position-relative">
                                    @if ($editable)
                                    <button id="edit-btn" class="btn btn-sm btn-primary ml-2 position-absolute top-0 end-0 translate-middle-y" style="font-size: 1.2rem;">                                                
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('home') }}" class="btn btn-secondary">                        
                                <i class="fas fa-arrow-left"></i> Go Back
                            </a>
                            <a href="{{ route('change-password') }}" class="btn btn-primary">                         
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var editable = @json($editable);

    document.addEventListener('DOMContentLoaded', function () {
        const editBtn = document.getElementById('edit-btn');
        const phoneNumber = document.getElementById('phone-number');
        const infoIcon = document.querySelector('.info-icon');
        const infoTooltip = document.querySelector('.info-tooltip');

        function handleEdit() {
            phoneNumber.removeAttribute('readonly');
            phoneNumber.focus();
            editBtn.innerHTML = '<i class="fas fa-save"></i>';
            editBtn.classList.remove('btn-primary');
            editBtn.classList.add('btn-success');

            editBtn.removeEventListener('click', handleEdit);
            editBtn.addEventListener('click', handleSubmit);
        }

        function handleSubmit() {
            if (confirm('Confirm to edit phone number?')) {
                const phoneNumberValue = phoneNumber.value;
                fetch('{{ route('update-phone') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ phone_number: phoneNumberValue })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        phoneNumber.setAttribute('readonly', 'readonly');
                        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                        editBtn.classList.remove('btn-success');
                        editBtn.classList.add('btn-primary');

                        const successAlert = document.createElement('div');
                        successAlert.classList.add('alert', 'alert-success');
                        successAlert.textContent = 'Phone number updated successfully';
                        const cardBody = document.querySelector('.card-body');
                        cardBody.insertBefore(successAlert, cardBody.firstChild);
                    } else {
                        alert('Failed to update phone number');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        if (editable && editBtn) {
            editBtn.addEventListener('click', handleEdit);
        }

        if (infoIcon) {
            infoIcon.addEventListener('mouseover', function () {
                infoTooltip.style.display = 'block';
            });

            infoIcon.addEventListener('mouseout', function () {
                infoTooltip.style.display = 'none';
            });
        }

        const otherPersonalDetailsInputs = document.querySelectorAll('.form-floating:not(:last-of-type) input');
        otherPersonalDetailsInputs.forEach(input => {
            input.addEventListener('click', function () {
                const popup = document.createElement('div');
                popup.classList.add('alert', 'alert-warning', 'position-absolute', 'start-50', 'top-50', 'translate-middle-x', 'text-center');
                popup.style.zIndex = '2';
                popup.innerHTML = 'Other personal details are <br><strong>not editable</strong> since they are registered under UTHM.<br>You may only edit your <strong>phone number.</strong>';
                document.querySelector('.card-body').appendChild(popup);
                // Remove the popup after 3 seconds
                setTimeout(() => {
                    popup.remove();
                }, 3000);
            });
        });
    });
</script>

@endsection
