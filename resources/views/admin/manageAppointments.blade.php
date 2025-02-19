@extends('layouts.admin')

@section('content')
<style>
    .completed {
        color: green;
    }

    .upcoming {
        color: orange;
    }

    .cancelled {
        color: red;
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

    .d-flex {
        display: flex;
    }

    .ml-3 {
        margin-left: 1rem;
    }

    .ml-2 {
        margin-left: 0.5rem;
    }

    .filter-label {
        font-size: 0.875rem;
        font-style: italic;
        color: grey;
    }

    th.sortable {
        cursor: pointer;
    }

    th.sortable.asc::after {
        content: ' ▲';
    }

    th.sortable.desc::after {
        content: ' ▼';
    }
</style>
<div class="row justify-content-center">
    <div>
        <div class="card">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Manage Appointments</span>
                @include('partials.tabs')
            </div>
            <div class="card-body">
                <div class="tab-content" id="adminTabsContent">
                    <div class="tab-pane fade show active" id="manageAppointments" role="tabpanel" aria-labelledby="manageAppointments-tab">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <ul class="nav nav-tabs flex-grow-1" id="bookingTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true" data-tab="upcoming">Upcoming</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false" data-tab="completed">Completed</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false" data-tab="cancelled">Cancelled</a>
                                </li>
                            </ul>
                            <form method="GET" action="{{ route('admin.manageAppointments') }}" class="d-flex flex-wrap justify-content-end">
                                <div class="form-group mx-2">
                                    <label for="filter_date" class="filter-label d-block">Sort by Date</label>
                                    <select name="filter_date" id="filter_date" class="form-control">
                                        <option value="earliest" {{ $filterDate === 'earliest' ? 'selected' : '' }}>Earliest</option>
                                        <option value="latest" {{ $filterDate === 'latest' ? 'selected' : '' }}>Latest</option>
                                    </select>
                                </div>
                                <div class="form-group mx-2">
                                    <label for="filter_service" class="filter-label d-block">Filter by Service</label>
                                    <select name="filter_service" id="filter_service" class="form-control">
                                        <option value="">All Services</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->service_type }}" {{ $filterService === $service->service_type ? 'selected' : '' }}>{{ $service->service_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mx-2">
                                    <label for="filter_assigned" class="filter-label d-block">Assigned to Staff</label>
                                    <select name="filter_assigned" id="filter_assigned" class="form-control">
                                        <option value="">All</option>
                                        <option value="assigned" {{ $filterAssigned === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="unassigned" {{ $filterAssigned === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-end mx-2">
                                    <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-filter"></i> Apply Filters</button>
                                    <a href="{{ route('admin.manageAppointments') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Clear Filters</a>
                                </div>
                            </form>
                        </div>          
                        <div class="tab-content mt-2" id="bookingTabsContent">
                            @foreach (['upcoming', 'completed', 'cancelled'] as $tab)
                            <div class="tab-pane fade {{ $tab == 'upcoming' ? 'show active' : '' }}" id="{{ $tab }}" role="tabpanel" aria-labelledby="{{ $tab }}-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center sortable" data-sort="appointment_id">Appointment ID</th>
                                            <th class="text-center sortable" data-sort="selected_service_type">Service</th>
                                            <th class="text-center sortable" data-sort="date">Date</th>
                                            <th class="text-center sortable" data-sort="time">Time</th>
                                            @if ($tab == 'completed')
                                            <th class="text-center sortable" data-sort="feedback">Feedback</th>
                                            <th class="text-center sortable" data-sort="rating">Rating</th>
                                            @endif
                                            <th class="text-center sortable" data-sort="assigned">Assigned</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse (${$tab . 'Appointments'} as $appointment)
                                        <tr>
                                            <td class="text-center" data-sort="appointment_id">{{ $appointment->appointment_id }}</td>
                                            <td class="text-center" data-sort="selected_service_type">{{ $appointment->selected_service_type }}</td>
                                            <td class="text-center" data-sort="date">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                                            <td class="text-center" data-sort="time">{{ \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('h:i A') }}</td>
                                            @if ($tab == 'completed')
                                            <td class="text-center" data-sort="feedback">
                                                @if ($appointment->feedbackRating)
                                                <em>{{ $appointment->feedbackRating->feedback }}</em>
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                            <td class="text-center" data-sort="rating">
                                                @if ($appointment->feedbackRating)
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
                                            N/A
                                            @endif
                                        </td>
                                        @endif
                                        <td class="text-center" data-sort="assigned">
                                            @if ($appointment->msID)
                                            {{ optional($appointment->medicalStaff)->name }}
                                            @else
                                            @if ($tab == 'upcoming')
                                            <a href="{{ route('admin.bookingDetails', ['appointmentId' => $appointment->appointment_id]) }}" class="btn btn-warning btn-sm">
                                                Assign
                                            </a>
                                            @else
                                            N/A
                                            @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.bookingDetails', ['appointmentId' => $appointment->appointment_id]) }}" class="btn btn-info">
                                                <i class="fas fa-info"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="{{ $tab == 'completed' ? '8' : '6' }}">No appointments.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                <ul class="pagination">
                                    <li class="page-item {{ ${$tab . 'Appointments'}->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ ${$tab . 'Appointments'}->url(1) }}" tabindex="-1">&lt;&lt;</a>
                                    </li>
                                    <li class="page-item {{ ${$tab . 'Appointments'}->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ ${$tab . 'Appointments'}->previousPageUrl() }}" tabindex="-1">&lt;</a>
                                    </li>
                                    @for($page = 1; $page <= ${$tab . 'Appointments'}->lastPage(); $page++)
                                    <li class="page-item {{ ${$tab . 'Appointments'}->currentPage() == $page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ ${$tab . 'Appointments'}->url($page) }}">{{ $page }}</a>
                                    </li>
                                    @endfor
                                    <li class="page-item {{ ${$tab . 'Appointments'}->currentPage() == ${$tab . 'Appointments'}->lastPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ ${$tab . 'Appointments'}->nextPageUrl() }}">&gt;</a>
                                    </li>
                                    <li class="page-item {{ ${$tab . 'Appointments'}->currentPage() == ${$tab . 'Appointments'}->lastPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ ${$tab . 'Appointments'}->url(${$tab . 'Appointments'}->lastPage()) }}">&gt;&gt;</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {

        $(document).ready(function () {
        var currentPath = window.location.pathname;
        if (currentPath.includes('/admin/manageAppointments')) {
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
        // Load the last selected tab from localStorage
        var lastSelectedTab = localStorage.getItem('lastSelectedTab');
        if (lastSelectedTab) {
            $('.nav-link[data-tab="' + lastSelectedTab + '"]').tab('show');
        }

        // Save the selected tab to localStorage when clicked
        $('.nav-link[data-tab]').on('click', function () {
            var selectedTab = $(this).data('tab');
            localStorage.setItem('lastSelectedTab', selectedTab);
        });

        // Highlight the active sidebar link based on the current path
        var currentPath = window.location.pathname;
        $('.nav-link').each(function () {
            var link = $(this).attr('href');
            if (currentPath.includes(link)) {
                $(this).addClass('active');
            }
        });

        // Sorting functionality
        $('th.sortable').on('click', function () {
            var table = $(this).closest('table');
            var tbody = table.find('tbody');
            var rows = tbody.find('tr').get();
            var column = $(this).data('sort');
            var order = $(this).hasClass('asc') ? 'desc' : 'asc';

            rows.sort(function (a, b) {
                var keyA = $(a).find('td[data-sort="' + column + '"]').text().toLowerCase();
                var keyB = $(b).find('td[data-sort="' + column + '"]').text().toLowerCase();

                if (column === 'date') {
                    // Extract month and year for comparison
                    var dateA = new Date(keyA.split('/').reverse().join('/'));
                    var dateB = new Date(keyB.split('/').reverse().join('/'));

                    var monthA = dateA.getMonth();
                    var yearA = dateA.getFullYear();
                    var monthB = dateB.getMonth();
                    var yearB = dateB.getFullYear();

                    if (yearA === yearB) {
                        return (monthA < monthB ? -1 : (monthA > monthB ? 1 : 0)) * (order === 'asc' ? 1 : -1);
                    } else {
                        return (yearA < yearB ? -1 : (yearA > yearB ? 1 : 0)) * (order === 'asc' ? 1 : -1);
                    }
                }

                if ($.isNumeric(keyA) && $.isNumeric(keyB)) {
                    keyA = parseFloat(keyA);
                    keyB = parseFloat(keyB);
                }

                if (keyA < keyB) return order === 'asc' ? -1 : 1;
                if (keyA > keyB) return order === 'asc' ? 1 : -1;
                return 0;
            });

            tbody.empty().append(rows);
            table.find('th.sortable').removeClass('asc desc');
            $(this).addClass(order);
        });    
    });

    document.addEventListener('DOMContentLoaded', function() {
        const assignButtons = document.querySelectorAll('.btn-warning.btn-sm');
        const cancelButtons = document.querySelectorAll('.btn-secondary');

        assignButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const confirmation = confirm('Are you sure you want to assign this appointment to a medical staff?');
                if (confirmation) {
                    window.location.href = button.getAttribute('href');
                }
            });
        });
    });
</script>
@endsection