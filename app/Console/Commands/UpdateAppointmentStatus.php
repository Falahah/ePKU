<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class UpdateAppointmentStatus extends Command
{
    protected $signature = 'appointments:update-status';
    protected $description = 'Update appointment status';

    public function handle()
    {
        $this->info('Updating appointment status...');

        // Get appointments that have passed the current date and time
        $appointments = Appointment::where('appointment_status', 'upcoming')
            ->where('date', '<', now())
            ->get();

        foreach ($appointments as $appointment) {
            // Update appointment status to 'completed'
            $appointment->update(['appointment_status' => 'completed']);
        }

        $this->info('Appointment status updated successfully.');
    }
}
