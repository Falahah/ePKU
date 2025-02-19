<style>
    .stars {
        font-size: 24px;
    }

    .star {
        color: #ccc;
    }

    .star.selected {
        color: gold;
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

<!-- Table Header -->
<table class="table mt-3">
    <thead>
        <tr>
            <th class="text-center sortable" data-sort="appointment_id">Appointment ID</th>
            <th class="text-center sortable" data-sort="selected_service_type">Service</th>
            <th class="text-center sortable" data-sort="date">Date</th>
            <th class="text-center sortable" data-sort="time">Time</th>
            <!-- Display Feedback and Rating columns only for 'Completed' tab -->
            @if ($tab == 'completed')
                <th class="text-center sortable" data-sort="feedback">Feedback</th>
                <th class="text-center sortable" data-sort="rating">Rating</th>
            @endif
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($appointments as $appointment)
            <tr>
                <!-- Table Data -->
                <td class="text-center" data-sort="appointment_id">{{ $appointment->appointment_id }}</td>
                <td class="text-center" data-sort="selected_service_type">{{ $appointment->selected_service_type }}</td>
                <td class="text-center" data-sort="date">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                <td class="text-center" data-sort="time">{{ \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('h:i A') }}</td>
                @if ($tab == 'completed')
                    <td class="text-center" data-sort="feedback">
                        @if ($appointment->feedbackRating)
                            {{ $appointment->feedbackRating->feedback }}
                        @else
                            <p>N/A</p>
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
                            <p>N/A</p>
                        @endif
                    </td>
                @endif
                <td class="text-center">
                    <a href="{{ route('booking-details', ['appointmentId' => $appointment->appointment_id]) }}" class="btn btn-primary"><i class="fas fa-info"></i> View Details</a>
                </td>
            </tr>
        @empty
            <!-- No Appointments Message -->
            <tr>
                <td colspan="{{ $tab == 'completed' ? '7' : '5' }}">No appointments.</td>
            </tr>
        @endforelse
    </tbody>
</table>