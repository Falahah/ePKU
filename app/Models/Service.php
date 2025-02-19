<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_type',
        'opening_hours',
        'closing_hours',
    ];

    // Define relationships if necessary
    // A Service has many appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'selected_service_type', 'service_type');
    }
}
