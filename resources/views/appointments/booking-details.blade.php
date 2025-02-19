@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <a href="{{ route('booking-history') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-center"><strong>Appointment Details</strong></h2>
                    <div></div>
                </div>

                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="col-md-12 mx-auto">
                        <div class="row">
                            <div class="col-sm-12 bg-light p-4 rounded mb-3">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Appointment ID</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $appointment->appointment_id }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Service</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $appointment->selected_service_type }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Date</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Time</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ $appointment->timeSlot ? \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('h:i A') : 'Not available' }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Status</strong>
                                    </div>
                                    <div class="col-sm-8 {{ $appointment->appointment_status }}">
                                        {{ ucfirst($appointment->appointment_status) }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <strong>Feedback <br>&<br> Rating</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        @if ($appointment->feedbackRating)
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    {{ $appointment->feedbackRating->feedback }}
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    @for ($i = 1; $i <= $appointment->feedbackRating->rating; $i++)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        @elseif ($appointment->appointment_status === 'completed')
                                            <span id="feedback-rating-placeholder">
                                                <button class="btn btn-primary" onclick="showFeedbackRatingForm()"><i class="fas fa-star"></i> Give Feedback & Rating</button>
                                            </span>
                                            <form id="feedbackRatingForm" method="POST" action="{{ route('feedback_rating.store') }}" style="display:none;">
                                                @csrf
                                                <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">
                                                <textarea id="feedback" name="feedback" class="form-control mt-2" placeholder="Leave your feedback here" required></textarea>
                                                <div class="starability-basic mt-2" onclick="setRating(event)">
                                                    <fieldset>
                                                        <span class="star" title="Terrible" data-value="1">&#9733;</span>
                                                        <span class="star" title="Not good" data-value="2">&#9733;</span>
                                                        <span class="star" title="Average" data-value="3">&#9733;</span>
                                                        <span class="star" title="Very good" data-value="4">&#9733;</span>
                                                        <span class="star" title="Excellent" data-value="5">&#9733;</span>
                                                    </fieldset>
                                                </div>
                                                <input type="hidden" id="selectedRating" name="rating" value="0">
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn btn-primary btn-sm mt-2" onclick="validateFeedbackRatingForm()"><i class="fas fa-check"></i> Submit</button>
                                                    <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="hideFeedbackRatingForm()"><i class="fas fa-times"></i> Cancel</button>
                                                </div>
                                            </form>
                                        @elseif ($appointment->appointment_status === 'upcoming')
                                            <p>You may give feedback and rating after the appointment has been completed.</p><br>
                                        @elseif ($appointment->appointment_status === 'cancelled')
                                            <p>No feedback and rating since the appointment has been cancelled.</p><br>
                                        @else
                                            N/A <br>
                                        @endif
                                        <br>
                                    </div>
                                    <hr>
                                    <div class="text-center mt-4">
                                        @if ($appointment->appointment_status !== 'completed' && $appointment->appointment_status !== 'cancelled')
                                            <form id="cancelForm" method="POST" action="{{ route('cancel-appointment', ['appointmentId' => $appointment->appointment_id]) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger mx-2" onclick="return confirmCancellation()"><i class="fas fa-times"></i> Cancel Appointment</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>                            
                            </div>
                        </dl>
                    </div>            
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmCancellation() {
        return confirm('Are you sure you want to cancel this appointment?');
    }

    function showFeedbackRatingForm() {
        document.getElementById('feedback-rating-placeholder').style.display = 'none';
        document.getElementById('feedbackRatingForm').style.display = 'block';
        document.querySelector('button[onclick="showFeedbackRatingForm()"]').style.display = 'none';
    }

    function hideFeedbackRatingForm() {
        if (confirm('Are you sure you want to cancel the feedback and rating submission?')) {
            document.getElementById('feedback-rating-placeholder').style.display = 'inline';
            document.getElementById('feedbackRatingForm').style.display = 'none';
            document.querySelector('button[onclick="showFeedbackRatingForm()"]').style.display = 'inline';
        }
    }

    function setRating(event) {
        const selectedRating = event.target.getAttribute('data-value');
        document.getElementById('selectedRating').value = selectedRating;

        const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            const value = star.getAttribute('data-value');
            star.classList.toggle('selected', value <= selectedRating);
        });
    }

    function validateFeedbackRatingForm() {
        const feedback = document.getElementById('feedback').value;
        const rating = document.getElementById('selectedRating').value;

        if (feedback.trim() === '') {
            alert('Please fill in the feedback field.');
            return;
        }

        if (rating === '0') {
            alert('Please select a rating.');
            return;
        }

        if (confirm('Are you sure you want to submit the feedback and rating?')) {
            document.getElementById('feedbackRatingForm').submit();
        }
    }</script>

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

    .star {
        font-size: 24px;
        cursor: pointer;
        color: #ccc;
    }

    .star.selected {
        color: gold;
    }

    .text-center.mt-4 button {
        margin-top: 10px;
    }
</style>
