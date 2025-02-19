<div class="container">
    <!-- Display announcements, reminder, and operating hours -->
    <div class="row">
        <!-- Announcements -->
        <div class="col-md-3">
            <div class="text-center">
                <hr>
                <h5><strong>Announcements</strong></h5>
                <hr>
                @forelse ($announcements as $announcement)
                    {{-- Check announcement visibility --}}
                    @if ($announcement->visible)
                        <div>
                            <h5><strong>{{ $announcement->title }}</strong></h5>
                            <p>{{ $announcement->content }}</p>
                        </div>
                        <hr>
                    @endif
                @empty
                    <p>No announcements available.</p>
                @endforelse
            </div>
        </div>

        <!-- Reminder -->
        <div class="col-md-3">
            <div style="text-align: center;">
                <hr>
                <h5><strong>Reminder</strong></h5>
                <hr>
                <p>Upon visiting PKU for your appointment, do remember to:</p>
                <p>📌 Bring along your Matric Card/Staff card</p>
                <p>📌 Wear a face mask</p>
            </div>
        </div>

        <!-- Medical Operating Hours -->
        <div class="col-md-3">
            <div style="text-align: center;">
                <hr>
                <h5><strong>Medical Operating Hours</strong></h5>
                <hr>
                <h6><strong>Medical Operating Hours</strong></h6>
                <p>📌 Sunday - Wednesday: 8.00 AM - 7.30 PM</p>
                <p>📌 Thursday: 8.00 AM - 6.00 PM</p>
            </div>
        </div>

        <!-- Dental Operating Hours -->
        <div class="col-md-3">
            <div style="text-align: center;">
                <hr>
                <h5><strong>Dental Operating Hours</strong></h5>
                <hr>
                <h6><strong>Dental Operating Hours</strong></h6>
                <p>📌 Sunday - Wednesday: 8.00 AM - 5.00 PM</p>
                <p>📌 Thursday: 8.00 AM - 3.30 PM</p>
            </div>
        </div>
    </div>
</div>


<!-- Full-Width Announcement Section -->
<div class="col-md-11 mx-auto">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><strong>{{ __('Announcements & Updates') }}</strong></h4>
                </div>
                <div class="card-body" style="height: auto;"> <!-- Remove fixed height -->
                    {{-- Announcement Section --}}
                    @include('partials._announcement', ['announcements' => $announcements])
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>