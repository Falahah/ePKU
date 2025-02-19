<?php

// app/Console/Commands/CompleteAppointments.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CompleteAppointments extends Command
{
    protected $signature = 'appointments:complete';

    protected $description = 'Complete appointments whose scheduled date has passed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all upcoming appointments where the scheduled date has passed
        $appointments = Appointment::where('date', '<', Carbon::now())
            ->where('appointment_status', 'upcoming')
            ->get();

        // Update the status of appointments to "completed"
        foreach ($appointments as $appointment) {
            $appointment->update(['appointment_status' => 'completed']);
        }

        $this->info('Completed ' . count($appointments) . ' appointments.');
    }
}
