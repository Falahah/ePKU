@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center text-primary"><strong>Book Appointment</strong></h2>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.store') }}" id="appointment-form">
                        @csrf
                        <!-- Select Service -->
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Select Service -->
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="selected_service_type" id="selected_service_type" required>
                                        <option value="">-- Select Service --</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->service_type }}">{{ $service->service_type }}</option>
                                        @endforeach
                                    </select>
                                    <label for="selected_service_type" class="form-label">Select Service</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Select Date -->
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" name="date" id="date" required>
                                    <label for="date" class="form-label">Select Date</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Select Time Slot -->
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="selected_time_slot" id="selected_time_slot" required>
                                        <option value="">-- Select Time Slot --</option>
                                        <!-- Options will be populated dynamically based on date selection -->
                                    </select>
                                    <label for="selected_time_slot" class="form-label">Select Time Slot</label>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Status -->
                        <div id="availability-status"></div>

                        <!-- Booking Summary -->
                        <div id="booking-summary" class="mb-4" style="display: none; border: 1px solid #ccc; padding: 15px; border-radius: 8px;">
                        <h4 class="mb-3 text-primary text-center"><strong>Booking Summary</strong></h4><hr>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Name</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ auth()->user()->name }}
                                </div>
                            </div>
                            <hr>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Username</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ auth()->user()->username }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Service</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span id="service-summary"></span>
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Date</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span id="date-summary"></span>
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Time Slot</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span id="time-slot-summary"></span>
                                </div>
                            </div>
                        </div>
                                                        
                        <div class="d-flex justify-content-center">
                            <!-- Buttons -->
                            <button type="button" class="btn btn-primary me-2" id="check-availability-btn">
                                <i class="fas fa-check"></i> Check Availability
                            </button>
                            <button type="submit" id="book-appointment-btn" class="btn btn-success me-2" style="display: none;">
                                <i class="fas fa-book"></i> Book Appointment  
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary" onclick="return confirmCancel()">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmCancel() {
        return confirm('Cancel booking?');
    }

    function checkAvailability() {
    var selectedService = document.querySelector('[name="selected_service_type"]').value;
    var selectedDate = document.getElementById('date').value;
    var selectedTimeSlotId = document.querySelector('[name="selected_time_slot"]').value;

    if (!selectedService || !selectedDate || !selectedTimeSlotId) {
        alert('Please select a service, date, and time slot before checking availability.');
        return;
    }

    fetch(`/check-availability/${selectedService}/${selectedDate}/${selectedTimeSlotId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            var availabilityStatus = document.getElementById('availability-status');
            if (data.available || data.cancelled) {
                availabilityStatus.innerHTML = '<div class="alert alert-success">Time slot is available!</div>';
                document.getElementById('book-appointment-btn').style.display = 'block';
                showBookingSummary();
            } else {
                availabilityStatus.innerHTML = '<div class="alert alert-danger">This time slot is already booked. Please select another time.</div>';
                document.getElementById('book-appointment-btn').style.display = 'none';
                document.getElementById('booking-summary').style.display = 'none';
            }

            document.getElementById('date').addEventListener('change', function () {
                hideAvailabilityStatus();
            });

            document.querySelector('[name="selected_time_slot"]').addEventListener('change', function () {
                hideAvailabilityStatus();
            });
        })
        .catch(error => {
            console.error('Error checking availability:', error);
        });
    }

    function hideAvailabilityStatus() {
        var availabilityStatus = document.getElementById('availability-status');
        availabilityStatus.innerHTML = '';
    }

    function showBookingSummary() {
        var selectedService = document.querySelector('[name="selected_service_type"]').value;
        var selectedDate = document.getElementById('date').value;
        var selectedTimeSlot = document.querySelector('[name="selected_time_slot"]').options[document.querySelector('[name="selected_time_slot"]').selectedIndex].text;

        var timeParts = selectedTimeSlot.split(':');
        var hours = parseInt(timeParts[0]);
        var minutes = timeParts[1].split(' ')[0];
        var ampm = timeParts[1].split(' ')[1];

        hours = hours % 12 || 12;

        var dateParts = selectedDate.split('-');
        var formattedDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];

        document.getElementById('service-summary').innerText = selectedService;
        document.getElementById('date-summary').innerText = formattedDate;
        document.getElementById('time-slot-summary').innerText = hours + ':' + minutes + ' ' + ampm;

        document.getElementById('booking-summary').style.display = 'block';
        document.getElementById('book-appointment-btn').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('[name="selected_service_type"]').addEventListener('change', showBookingSummary);
    document.getElementById('date').addEventListener('input', showBookingSummary);
    document.querySelector('[name="selected_time_slot"]').addEventListener('change', function(){
        document.getElementById('booking-summary').style.display = 'none';
        document.getElementById('book-appointment-btn').style.display = 'none';
    });

    document.getElementById('check-availability-btn').addEventListener('click', function () {
        checkAvailability();
    });

    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var currentDay = ('0' + currentDate.getDate()).slice(-2);

    document.getElementById('date').setAttribute('min', currentYear + '-' + currentMonth + '-' + currentDay);

    if (currentDate.getHours() >= 19) {
        var today = new Date();
        var tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        document.getElementById('date').setAttribute('min', tomorrow.toISOString().split('T')[0]);
        alert('Booking for today are closed. Please select a date starting from tomorrow.');
    }

    var blankOption = document.createElement('option');
    blankOption.value = '';
    blankOption.text = '-- Select Time Slot --';
    document.querySelector('[name="selected_time_slot"]').add(blankOption);

    var timeSlots = @json($timeSlots);
    var timeSlotSelect = document.querySelector('[name="selected_time_slot"]');
    var dateInput = document.getElementById('date');
    var serviceSelect = document.querySelector('[name="selected_service_type"]');

    function getServiceTimeRestrictions(service) {
        @foreach($services as $service)
            if (service === '{{ $service->service_type }}') {
                return {
                    '0': { start: '{{ $service->sunday_opening }}', end: '{{ $service->sunday_closing }}' },
                    '1': { start: '{{ $service->monday_opening }}', end: '{{ $service->monday_closing }}' },
                    '2': { start: '{{ $service->tuesday_opening }}', end: '{{ $service->tuesday_closing }}' },
                    '3': { start: '{{ $service->wednesday_opening }}', end: '{{ $service->wednesday_closing }}' },
                    '4': { start: '{{ $service->thursday_opening }}', end: '{{ $service->thursday_closing }}' },
                    '5': { start: '{{ $service->friday_opening }}', end: '{{ $service->friday_closing }}' },
                    '6': { start: '{{ $service->saturday_opening }}', end: '{{ $service->saturday_closing }}' }
                };
            }
        @endforeach
    }

    dateInput.addEventListener('input', function () {
        updateAvailableTimeSlots();
    });

    serviceSelect.addEventListener('change', updateAvailableTimeSlots);

    function updateAvailableTimeSlots() {
        var selectedService = serviceSelect.value;
        var selectedDate = new Date(dateInput.value);
        var selectedDay = selectedDate.getDay();

        timeSlotSelect.innerHTML = '';
        timeSlotSelect.add(blankOption);

        if (selectedService && selectedDate) {
            var restrictions = getServiceTimeRestrictions(selectedService)[selectedDay];
            var currentTime = new Date();

            for (var i = 0; i < timeSlots.length; i++) {
                var timeSlot = timeSlots[i];
                var timeParts = timeSlot.start_time.split(':');
                var slotHour = parseInt(timeParts[0]);
                var slotMinute = parseInt(timeParts[1]);

                if (
                    (slotHour > parseInt(restrictions.start.split(':')[0]) ||
                    (slotHour === parseInt(restrictions.start.split(':')[0]) && slotMinute >= parseInt(restrictions.start.split(':')[1]))) &&
                    (slotHour < parseInt(restrictions.end.split(':')[0]) ||
                    (slotHour === parseInt(restrictions.end.split(':')[0]) && slotMinute < parseInt(restrictions.end.split(':')[1])))
                ) {
                    if (selectedDate.toDateString() !== currentTime.toDateString() || 
                        (slotHour > currentTime.getHours() || (slotHour === currentTime.getHours() && slotMinute > currentTime.getMinutes()))) {
                        
                        var ampm = slotHour >= 12 ? 'PM' : 'AM';
                        var hours = slotHour % 12 || 12;

                        var option = document.createElement('option');
                        option.value = timeSlot.time_id;
                        option.text = hours + ':' + ('0' + slotMinute).slice(-2) + ' ' + ampm;
                        timeSlotSelect.add(option);
                    }
                }
            }
        }
    }

    dateInput.addEventListener('focusout', function () {
        var selectedDate = new Date(this.value);
        var selectedDay = selectedDate.getDay();
        var selectedService = serviceSelect.value;

        var disabledDays = [5, 6]; // Assuming Friday and Saturday are disabled
        if (disabledDays.includes(selectedDay)) {
            alert('Fridays and Saturdays are not available. Please choose another date.');
            this.value = '';
        }

        var closingTime = getServiceClosingTime(selectedService, selectedDay);
        if (selectedDate.toDateString() === new Date().toDateString() && currentDate.getHours() >= closingTime) {
            alert('Booking after ' + formatTime(closingTime) + ' is not allowed for today. Please select another date.');
            this.value = '';
        }

        function getServiceClosingTime(service, day) {
            var serviceRestrictions = getServiceTimeRestrictions(service);
            return serviceRestrictions[day].end;
        }

        function formatTime(time) {
            var timeParts = time.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = timeParts[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            return hours + ':' + minutes + ' ' + ampm;
        }
    });

    document.getElementById('book-appointment-btn').addEventListener('click', function (event) {
        event.preventDefault();

        var selectedService = document.querySelector('[name="selected_service_type"]').value;
        var selectedDate = document.getElementById('date').value;
        var selectedTimeSlotId = document.querySelector('[name="selected_time_slot"]').value;

        fetch(`/check-availability/${selectedService}/${selectedDate}/${selectedTimeSlotId}`)
            .then(response => response.json())
            .then(data => {
                if (data.available || data.cancelled) {
                    if (confirm("Confirm booking?")) {
                        document.getElementById('appointment-form').submit(); 
                    }
                } else {
                    alert('This time slot is already booked. Please select another time.');
                }
            })
            .catch(error => {
                console.error('Error checking availability:', error);
                alert('Error checking availability. Please try again.');
            });
    });
});
</script>

@endsection
