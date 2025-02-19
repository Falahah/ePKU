@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center">
        <ul class="nav nav-tabs" id="bookingHistoryTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="upcoming-booking-tab" data-toggle="tab" href="#upcoming-booking" role="tab" aria-controls="upcoming-booking" aria-selected="false">Upcoming</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="completed-booking-tab" data-toggle="tab" href="#completed-booking" role="tab" aria-controls="completed-booking" aria-selected="false">Completed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cancelled-booking-tab" data-toggle="tab" href="#cancelled-booking" role="tab" aria-controls="cancelled-booking" aria-selected="false">Cancelled</a>
            </li>
        </ul>
        <br>
        <form method="GET" action="{{ route('booking-history') }}" class="col-md-8">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_date">Sort by Date</label>
                        <select name="filter_date" id="filter_date" class="form-control">
                            <option value="earliest" {{ $filterDate === 'earliest' ? 'selected' : '' }}>Earliest</option>
                            <option value="latest" {{ $filterDate === 'latest' ? 'selected' : '' }}>Latest</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_service">Filter by Service</label>
                        <select name="filter_service" id="filter_service" class="form-control">
                            <option value="">All Services</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->service_type }}" {{ $filterService === $service->service_type ? 'selected' : '' }}>{{ $service->service_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filters</button>
                    <a href="{{ route('booking-history') }}" class="btn btn-secondary ml-2"><i class="fas fa-times"></i> Clear Filters</a>
                </div>
            </div>
        </form>
        <div>
            <a href="{{ route('home') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Go Back</a>
        </div>
    </div>

    <div class="tab-content mt-2" id="bookingHistoryTabsContent">
        <div class="tab-pane fade" id="upcoming-booking" role="tabpanel" aria-labelledby="upcoming-booking-tab">
            @include('partials.booking-history-table', ['appointments' => $upcomingAppointments, 'tab' => 'upcoming'])
            <div class="d-flex justify-content-center mt-3">
                <ul class="pagination">
                    <li class="page-item {{ $upcomingAppointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $upcomingAppointments->url(1) }}" tabindex="-1">&lt;&lt;</a>
                    </li>
                    <li class="page-item {{ $upcomingAppointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $upcomingAppointments->previousPageUrl() }}" tabindex="-1">&lt;</a>
                    </li>
                    @for($page = 1; $page <= $upcomingAppointments->lastPage(); $page++)
                        <li class="page-item {{ $upcomingAppointments->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $upcomingAppointments->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $upcomingAppointments->currentPage() == $upcomingAppointments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $upcomingAppointments->nextPageUrl() }}">&gt;</a>
                    </li>
                    <li class="page-item {{ $upcomingAppointments->currentPage() == $upcomingAppointments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $upcomingAppointments->url($upcomingAppointments->lastPage()) }}">&gt;&gt;</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane fade" id="completed-booking" role="tabpanel" aria-labelledby="completed-booking-tab">
            @include('partials.booking-history-table', ['appointments' => $completedAppointments, 'tab' => 'completed'])
            <div class="d-flex justify-content-center mt-3">
                <ul class="pagination">
                    <li class="page-item {{ $completedAppointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $completedAppointments->url(1) }}" tabindex="-1">&lt;&lt;</a>
                    </li>
                    <li class="page-item {{ $completedAppointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $completedAppointments->previousPageUrl() }}" tabindex="-1">&lt;</a>
                    </li>
                    @for($page = 1; $page <= $completedAppointments->lastPage(); $page++)
                        <li class="page-item {{ $completedAppointments->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $completedAppointments->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $completedAppointments->currentPage() == $completedAppointments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $completedAppointments->nextPageUrl() }}">&gt;</a>
                    </li>
                    <li class="page-item {{ $completedAppointments->currentPage() == $completedAppointments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $completedAppointments->url($completedAppointments->lastPage()) }}">&gt;&gt;</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane fade" id="cancelled-booking" role="tabpanel" aria-labelledby="cancelled-booking-tab">
            @include('partials.booking-history-table', ['appointments' => $cancelledAppointments, 'tab' => 'cancelled'])
            <div class="d-flex justify-content-center mt-3">
                <ul class="pagination">
                    <li class="page-item {{ $cancelledAppointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $cancelledAppointments->url(1) }}" tabindex="-1">&lt;&lt;</a>
                    </li>
                    <li class="page-item {{ $cancelledAppointments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $cancelledAppointments->previousPageUrl() }}" tabindex="-1">&lt;</a>
                    </li>
                    @for($page = 1; $page <= $cancelledAppointments->lastPage(); $page++)
                        <li class="page-item {{ $cancelledAppointments->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $cancelledAppointments->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $cancelledAppointments->currentPage() == $cancelledAppointments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $cancelledAppointments->nextPageUrl() }}">&gt;</a>
                    </li>
                    <li class="page-item {{ $cancelledAppointments->currentPage() == $cancelledAppointments->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $cancelledAppointments->url($cancelledAppointments->lastPage()) }}">&gt;&gt;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            const tabElement = document.querySelector(`#${activeTab}-tab`);
            if (tabElement) {
                document.querySelectorAll('#bookingHistoryTabs .nav-link').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));

                tabElement.classList.add('active');
                document.querySelector(`#${activeTab}`).classList.add('show', 'active');
            }
        } else {
            document.querySelector('#upcoming-booking-tab').classList.add('active');
            document.querySelector('#upcoming-booking').classList.add('show', 'active');
        }

        const tabLinks = document.querySelectorAll('#bookingHistoryTabs .nav-link');
        tabLinks.forEach(tab => {
            tab.addEventListener('click', function () {
                localStorage.setItem('activeTab', this.getAttribute('aria-controls'));
            });
        });

        // Sorting functionality
        const getSortOrder = () => localStorage.getItem('sortOrder') || 'asc';
        const setSortOrder = (order) => localStorage.setItem('sortOrder', order);
        const getSortColumn = () => localStorage.getItem('sortColumn') || 'appointment_id';
        const setSortColumn = (column) => localStorage.setItem('sortColumn', column);

        const sortTable = (column) => {
            const sortOrder = getSortOrder();
            const table = document.querySelector('.tab-pane.show .table tbody');
            const rows = Array.from(table.rows);

            rows.sort((a, b) => {
                let cellA = a.querySelector(`td[data-sort="${column}"]`).innerText.toLowerCase();
                let cellB = b.querySelector(`td[data-sort="${column}"]`).innerText.toLowerCase();

                if (column === 'date') {
                    // Extract month and year for comparison
                    const dateA = new Date(cellA.split('/').reverse().join('/'));
                    const dateB = new Date(cellB.split('/').reverse().join('/'));

                    const monthA = dateA.getMonth();
                    const yearA = dateA.getFullYear();
                    const monthB = dateB.getMonth();
                    const yearB = dateB.getFullYear();

                    if (yearA === yearB) {
                        return (monthA < monthB ? -1 : (monthA > monthB ? 1 : 0)) * (sortOrder === 'asc' ? 1 : -1);
                    } else {
                        return (yearA < yearB ? -1 : (yearA > yearB ? 1 : 0)) * (sortOrder === 'asc' ? 1 : -1);
                    }
                }

                return (cellA < cellB ? -1 : (cellA > cellB ? 1 : 0)) * (sortOrder === 'asc' ? 1 : -1);
            });

            table.innerHTML = '';
            rows.forEach(row => table.appendChild(row));
        };

        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const column = th.dataset.sort;
                const currentOrder = getSortOrder();
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                setSortOrder(newOrder);
                setSortColumn(column);
                sortTable(column);

                document.querySelectorAll('.sortable').forEach(th => th.classList.remove('asc', 'desc'));
                th.classList.add(newOrder);
            });
        });

        // Initial sort
        sortTable(getSortColumn());
    });
</script>

@endsection

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
