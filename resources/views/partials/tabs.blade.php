<!-- Add jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<ul class="nav nav-tabs card-header-tabs">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}" data-tab="dashboard">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manageAppointments') }}" data-tab="manageAppointments">Appointments</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manageUsers') }}" data-tab="manageUsers">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manageMedStaff') }}" data-tab="manageMedStaff">Medical Staffs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manageMedServices') }}" data-tab="manageMedServices">Medical Services</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manageTimeSlots') }}" data-tab="manageTimeSlots">Time Slots</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manageAnnouncements') }}" data-tab="manageAnnouncements">Announcements</a>
    </li>
</ul>
