@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Give Feedback and Rating') }}</div>

                <div class="card-body">
                    <form id="feedbackForm" method="POST" action="{{ route('feedback_rating.store') }}">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <textarea id="feedback" name="feedback" class="form-control" placeholder="Leave your feedback here" required></textarea>
                                    <label for="feedback">Feedback</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form">
                                    <label for="rating">Rating</label>
                                    <div class="starability-basic" onclick="setRating(event)">
                                        <fieldset>
                                            <span class="star" title="Terrible" data-value="1">&#9733;</span>
                                            <span class="star" title="Not good" data-value="2">&#9733;</span>
                                            <span class="star" title="Average" data-value="3">&#9733;</span>
                                            <span class="star" title="Very good" data-value="4">&#9733;</span>
                                            <span class="star" title="Excellent" data-value="5">&#9733;</span>
                                        </fieldset>
                                    </div>
                                    <input type="hidden" id="selectedRating" name="rating" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary mx-2" onclick="validateForm()">Submit</button>
                            <a href="{{ route('booking-history') }}" class="btn btn-secondary mx-2" onclick="return confirmCancel()">Cancel</a>
                        </div>

                        <script>
                            function confirmCancel() {
                                return confirm('Are you sure you want to cancel the feedback and rating submission?');
                            }

                            function setRating(event) {
                                const stars = document.querySelectorAll('.star');
                                const selectedRating = event.target.getAttribute('data-value');

                                // Update the hidden input with the selected rating
                                document.getElementById('selectedRating').value = selectedRating;

                                // Highlight the selected stars
                                stars.forEach(star => {
                                    const value = star.getAttribute('data-value');
                                    star.classList.toggle('selected', value <= selectedRating);
                                });
                            }

                            function validateForm() {
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

                                // Confirm submission
                                if (confirm('Are you sure you want to submit the feedback and rating?')) {
                                    // If confirmed, submit the form
                                    document.getElementById('feedbackForm').submit();
                                }
                            }
                        </script>
                        <style>
                            .star {
                                font-size: 24px;
                                cursor: pointer;
                                color: #ccc;
                            }

                            .star.selected {
                                color: gold;
                            }
                        </style>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
