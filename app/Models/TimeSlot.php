<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $primaryKey = 'time_id'; // Specify the primary key name if different from 'id'
    protected $table = 'time_slots';
    
    // Other model configurations and relationships can be added here
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'selected_time_slot', 'time_id');
    }
}
