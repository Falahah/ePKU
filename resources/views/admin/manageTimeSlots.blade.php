@extends('layouts.admin')

@section('content')
<style>
    .btn-close-white {
        background-color: white;
        opacity: 1;
    }
    .modal-header {
        background-color: orangered;
    }
</style>
<div class="row justify-content-center">
    <div>
        <div class="card">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Manage Time Slots</span>
                @include('partials.tabs') 
            </div>
            <div class="card-body">
                <div class="tab-content" id="adminTabsContent">
                    <div class="tab-pane fade show active" id="manageAppointments" role="tabpanel" aria-labelledby="manageAppointments-tab">
                        <h2 class="text-center text-primary"><strong>Manage Time Slots</strong></h2><hr>
                        <div class="container mt-4">
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                            <form method="GET" action="{{ route('admin.manageTimeSlots') }}">
                                <div class="row g-3 align-items-center">
                                    <!-- Select Date -->
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" name="date" id="date" value="{{ request('date') }}" required>
                                            <label for="date" class="form-label">Select Date</label>
                                        </div>
                                    </div>
                                    <!-- Select Service -->
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="service" id="service" required>
                                                <option value="">-- Select Service --</option>
                                                @foreach ($services as $service)
                                                    <option value="{{ $service->service_type }}" {{ request('service') == $service->service_type ? 'selected' : '' }}>{{ $service->service_type }}</option>
                                                @endforeach
                                            </select>
                                            <label for="service" class="form-label">Select Service</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-3">View Time Slots</button>
                                </div>
                            </form>
                            @if(request('date') && request('service'))
                                <div id="time-slots-section" class="mt-4">
                                    <hr>
                                    <div class="d-flex flex-wrap justify-content-center">
                                        @foreach ($timeSlots as $index => $timeSlot)
                                            <div class="p-1" style="width: 100px;">
                                                <button 
                                                    class="btn {{ in_array($timeSlot->time_id, $bookedTimeSlots) ? 'btn-danger' : 'btn-success' }} w-100" 
                                                    onclick="showAppointmentDetails({{ $timeSlot->time_id }})"
                                                    {{ in_array($timeSlot->time_id, $bookedTimeSlots) ? '' : 'disabled' }}>
                                                    {{ \Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }}
                                                </button>
                                            </div>
                                            @if(($index + 1) % 10 == 0)
                                                <div class="w-100"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header text-white">
            <h5 class="modal-title w-100 text-center" id="appointmentDetailsModalLabel">Appointment Details</h5>
            <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Service</th>
                        <td id="appointment_service"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td id="appointment_date"></td>
                    </tr>
                    <tr>
                        <th>Time Slot</th>
                        <td id="time_slot"></td>
                    </tr>
                    <tr>
                        <th>Booked By</th>
                        <td id="booked_by"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="status"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/admin/manageTimeSlots')) {
            $('.nav-link[data-tab="manageTimeSlots"]').addClass('active');
        } else {
            $('.nav-link').each(function () {
                var link = $(this).attr('href');
                if (currentPath === link) {
                    $(this).addClass('active');
                }
            });
        }
    });

    function showAppointmentDetails(timeSlotId) {
        fetch(`/appointments/${timeSlotId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('appointment_service').textContent = data.service;
                document.getElementById('appointment_date').textContent = data.date;
                document.getElementById('time_slot').textContent = data.time_slot;
                document.getElementById('booked_by').textContent = data.booked_by;
                document.getElementById('status').textContent = data.status;

                var appointmentDetailsModal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
                appointmentDetailsModal.show();
            })
            .catch(error => {
                console.error('Error fetching appointment details:', error);
                alert('Error fetching appointment details. Please try again.');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(request('date') && request('service'))
            document.getElementById('time-slots-section').style.display = 'block';
        @endif
    });
</script>

@endsection