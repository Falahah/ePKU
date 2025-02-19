<?php
// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\AppointmentBooked;
use App\Events\AppointmentCancelled;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'user_id',
        'msID',
        'selected_service_type',
        'selected_time_slot',
        'date',
        'feedback_rating',
        'appointment_status',
        'appointment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'selected_service_type', 'service_type');
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class, 'selected_time_slot', 'time_id');
    }

    public function feedbackRating()
    {
        return $this->hasOne(FeedbackRating::class, 'related_appointment_id', 'appointment_id');
    }

    public function medicalStaff()
    {
        return $this->belongsTo(MedicalStaff::class, 'msID', 'msID');
    }
}
