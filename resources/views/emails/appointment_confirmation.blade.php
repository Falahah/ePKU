<!DOCTYPE html>
<html>
<head>
    <title>Appointment Booking Confirmation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }

        .StyledReceipt {
            background-color: #fff;
            width: 90%;
            position: relative;
            padding: 1rem;
            box-shadow: 0px 0px 100px rgba(0, 0, 0, 0.2); /* horizontal-offset vertical-offset blur-radius color */
            margin-top: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .StyledReceipt::before {
            background-image: linear-gradient(-45deg, #fff 0.5rem, transparent 0),
                linear-gradient(45deg, #fff 0.5rem, transparent 0);
            background-position: left top;
            background-repeat: repeat-x;
            background-size: 1rem;
            content: '';
            display: block;
            position: absolute;
            top: -1rem;
            left: 0;
            width: 100%;
            height: 1rem;
        }

        .StyledReceipt::after {
            background-image: linear-gradient(135deg, #fff 0.5rem, transparent 0),
                linear-gradient(-135deg, #fff 0.5rem, transparent 0);
            background-position: left bottom;
            background-repeat: repeat-x;
            background-size: 1rem;
            content: '';
            display: block;
            position: absolute;
            left: 0;
            width: 100%;
            height: 1rem;
            bottom: -1rem;
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-logo img {
            max-width: 150px;
        }

        .header-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-title hr{
            border-top: 2px solid #000;
        }

        .header-title h2 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h4 {
            margin: 0;
            font-weight: 600;
            color: #007bff;
        }

        .header hr {
            margin: 10px 0;
            border: 0;
        }

        .receipt-details {
            margin-top: 20px;
        }

        .receipt-details .row {
            display: flex;
            margin-bottom: 10px;
        }

        .receipt-details .col-sm-4 {
            font-weight: bold;
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
        }

        .receipt-details .col-sm-8 {
            flex: 0 0 66.6667%;
            max-width: 66.6667%;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #555;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer strong {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="StyledReceipt">
    <!--<div class="header-logo">
        <img src="{{ asset('img/ePKU.png') }}" alt="ePKU Logo">
    </div>-->
    <div class="header-title">
        <h2 class="text-primary">Appointment Booking Confirmation</h2>
        <hr>
    </div>
    <div class="header">
        <h3 class="text-center">Appointment's Details</h3>
        <hr style="border-top: 1px dashed #000;">
    </div>
    <div class="receipt-details">
        <div class="row mb-3">
            <div class="col-sm-4">
                <strong>Appointment ID</strong>
            </div>
            <div class="col-sm-8">
                <span>{{ $appointment->appointment_id }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4">
                <strong>Name</strong>
            </div>
            <div class="col-sm-8">
                {{ $appointment->user->name }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4">
                <strong>Username</strong>
            </div>
            <div class="col-sm-8">
                {{ $appointment->user->username }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4">
                <strong>Service</strong>
            </div>
            <div class="col-sm-8">
                <span>{{ $appointment->service->service_type }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4">
                <strong>Date</strong>
            </div>
            <div class="col-sm-8">
                <span>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4">
                <strong>Time Slot</strong>
            </div>
            <div class="col-sm-8">
                <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->timeSlot->start_time)->format('h:i A') }}</span>
            </div>
        </div>
    </div>
    <hr style="border-top: 1px dashed #000;">
    <div class="footer">
        <p><strong>Thank you for booking!</strong></p>
        <p>Please mention your appointment ID to the receptionist.</p>
        <p style="text-align: left;">Please remember to:</p>
        <p style="text-align: left;">📌 Bring along your Matric Card/Staff card</p>
        <p style="text-align: left;">📌 Wear a face mask</p>
    </div>
</div>

</body>
</html>
