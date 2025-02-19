@extends('layouts.admin')

@section('content')

<style>
    .upcoming {
        color: orange;
        font-weight: bold;
    }
    .completed {
        color: green;
        font-weight: bold;
    }
    .cancelled {
        color: red;
        font-weight: bold;
    }
    .stars {
        font-size: 24px;
    }
    .star {
        color: #ccc;
    }
    .star.selected {
        color: gold;
    }
</style>

<div class="row justify-content-center">
    <div>
        <div class="card">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Appointment Details</span>
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>
            <div class="card-body">
                <div class="mx-auto d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.manageAppointments') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a> 
                    <h2 class="text-center text-primary"><strong>Appointment Details</strong></h2><br>
                </div><br>

                <!-- Check for success message and display it -->
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Appointment ID</th>
                            <td>{{ $appointment->appointment_id }}</td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>{{ $appointment->selected_service_type }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $appointment->timeSlot ? \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('h:i A') : 'Not available' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td style="
                                color: {{ $appointment->appointment_status == 'upcoming' ? 'orange' : '' }};
                                color: {{ $appointment->appointment_status == 'completed' ? 'green' : '' }};
                                color: {{ $appointment->appointment_status == 'cancelled' ? 'red' : '' }};
                                font-weight: bold;">
                                {{ ucfirst($appointment->appointment_status) }}
                            </td>
                        </tr>
                        <tr>
                            <th>Feedback</th>
                            <td>
                                @if ($appointment->appointment_status === 'completed')
                                    <em>{{ $appointment->feedbackRating ? $appointment->feedbackRating->feedback : 'Not available' }}</em>
                                @elseif ($appointment->appointment_status === 'upcoming')
                                    <em>Feedback will be available after the appointment.</em>
                                @elseif ($appointment->appointment_status === 'cancelled')
                                    <em>Feedback is not available since the appointment is cancelled.</em>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Rating</th>
                            <td>
                                @if ($appointment->appointment_status === 'completed' && $appointment->feedbackRating)
                                    <span class="stars">
                                        @php
                                            $rating = $appointment->feedbackRating->rating;
                                            $filledStars = min(5, $rating);
                                            $emptyStars = max(0, 5 - $rating);
                                        @endphp
                                        @for ($i = 0; $i < $filledStars; $i++)
                                            <span class="star selected">&#9733;</span>
                                        @endfor
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <span class="star">&#9733;</span>
                                        @endfor
                                    </span>
                                @else
                                    @if ($appointment->appointment_status === 'completed')
                                        <em>Not available</em>
                                    @elseif ($appointment->appointment_status === 'upcoming')
                                        <em>Rating will be available after the appointment.</em>
                                    @elseif ($appointment->appointment_status === 'cancelled')
                                        <em>Rating is not available since the appointment is cancelled.</em>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @if ($appointment->appointment_status === 'upcoming')
                            <tr>
                                <th>Assigned Medical Staff</th>
                                <td>
                                    <div id="assigned-staff-section">
                                        @if ($appointment->msID)
                                            {{ optional($appointment->medicalStaff)->name }}
                                            <button id="reassign-button" class="btn btn-warning btn-sm ml-2">Re-Assign</button>
                                        @else
                                            <form method="POST" action="{{ route('admin.assignMedStaff', ['appointmentId' => $appointment->appointment_id]) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="msID">Select Medical Staff:</label>
                                                    <select name="msID" id="msID" class="form-control" required>
                                                        <option value="">-- Select Medical Staff --</option>
                                                        @foreach ($medicalStaff as $staff)
                                                            <option value="{{ $staff->msID }}" {{ $appointment->msID == $staff->msID ? 'selected' : '' }}>
                                                                {{ $staff->name }} ({{ $staff->position }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Assign</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div id="reassign-section" style="display: none;">
                                        <form method="POST" action="{{ route('admin.assignMedStaff', ['appointmentId' => $appointment->appointment_id]) }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="msID">Select Medical Staff:</label>
                                                <select name="msID" id="msID" class="form-control" required>
                                                    <option value="">-- Select Medical Staff --</option>
                                                    @foreach ($medicalStaff as $staff)
                                                        <option value="{{ $staff->msID }}" {{ $appointment->msID == $staff->msID ? 'selected' : '' }}>
                                                            {{ $staff->name }} ({{ $staff->position }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Assign</button>
                                            <button type="button" id="cancel-reassign-button" class="btn btn-secondary">Cancel</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var reassignButton = document.getElementById('reassign-button');
        var reassignSection = document.getElementById('reassign-section');
        var assignedStaffSection = document.getElementById('assigned-staff-section');
        var cancelReassignButton = document.getElementById('cancel-reassign-button');

        if (reassignButton) {
            reassignButton.addEventListener('click', function () {
                assignedStaffSection.style.display = 'none';
                reassignSection.style.display = 'block';
            });
        }

        if (cancelReassignButton) {
            cancelReassignButton.addEventListener('click', function () {
                reassignSection.style.display = 'none';
                assignedStaffSection.style.display = 'block';
            });
        }
    });

    // Function to set the active tab based on the current URL
    $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/admin/booking-details')) {
            $('.nav-link[data-tab="manageAppointments"]').addClass('active');
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