<?php
// app/Models/FeedbackRating.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackRating extends Model
{
    use HasFactory;

    protected $table = 'feedback_rating';

    protected $fillable = [
        'related_appointment_id',
        'feedback',
        'rating',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'related_appointment_id');
    }
}
