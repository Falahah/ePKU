@extends('layouts.app')

@section('content')
<style>
    .StyledReceipt {
        background-color: #fff;
        width: 100%;
        position: relative;
        padding: 1rem;
        box-shadow: 0px 0px 100px rgba(0, 0, 0, 0.2); /* horizontal-offset vertical-offset blur-radius color */
        margin-top: 1rem;
        margin-bottom: 1rem;
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

    .receipt-details {
        margin-top: 20px;
    }

    .receipt-details .row {
        margin-bottom: 10px;
    }

    .receipt-details .col-sm-4 {
        font-weight: bold;
    }

    .receipt-details hr {
        border-top: 2px solid #000;
    }

    .receipt .text-center {
        margin-top: 20px;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">{{ __('Cancellation Confirmed') }}</h3>
                </div>

                <div class="card-body">
                    @if(session('cancellationDetails') && auth()->user())
                        @php
                            $cancellationDetails = session('cancellationDetails');
                            $timeSlot = \App\Models\TimeSlot::find($cancellationDetails['timeSlot']);
                            $formattedTime = \Carbon\Carbon::createFromFormat('H:i:s', $timeSlot->start_time)->format('h:i A');
                        @endphp
                        <div class="StyledReceipt">
                            <div class="header">
                                <h4 class="mb-3 text-primary text-center"><strong>Cancellation Details</strong></h4><hr>
                            </div>
                            <div class="receipt-details">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Appointment ID</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <span>{{ $cancellationDetails['appointment_id'] }}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Name</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ auth()->user()->name }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Username</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        {{ auth()->user()->username }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Service</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <span>{{ $cancellationDetails['service'] }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Date</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <span>{{ \Carbon\Carbon::parse($cancellationDetails['date'])->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <strong>Time Slot</strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <span>{{ $formattedTime }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4 text-center"><strong>Your appointment has been successfully cancelled.</strong></p>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Go to Home</a>
                        </div>
                    @else
                        <p class="text-center">No cancellation details found or user not logged in.</p>
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Go to Home</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
