<?php
namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Models\Notification;

class SendCancelledAppointmentNotification
{
    public function handle(AppointmentCancelled $event)
    {
        Notification::create([
            'type' => 'cancelled',
            'data' => 'An appointment has been cancelled for ' . $event->appointment->selected_service_type . ' on ' . $event->appointment->date,
            'read' => false,
            'appointment_id' => $event->appointment->appointment_id,
        ]);
    }
}
