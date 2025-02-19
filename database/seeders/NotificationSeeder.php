<?php

// database/seeders/NotificationSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        Notification::create([
            'type' => 'booked',
            'data' => 'A new appointment has been booked for General Checkup on 2023-06-15',
            'read' => false,
        ]);

        Notification::create([
            'type' => 'cancelled',
            'data' => 'An appointment has been cancelled for Dental Cleaning on 2023-06-10',
            'read' => false,
        ]);
    }
}
