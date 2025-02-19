<?php
// app/Providers/EventServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\AppointmentBooked;
use App\Events\AppointmentCancelled;
use App\Listeners\SendBookedAppointmentNotification;
use App\Listeners\SendCancelledAppointmentNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AppointmentBooked::class => [
            SendBookedAppointmentNotification::class,
        ],
        AppointmentCancelled::class => [
            SendCancelledAppointmentNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
