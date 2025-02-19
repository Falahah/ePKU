<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\FeedbackRating;
use Illuminate\Support\Facades\Log;

class FeedbackRatingController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request Data: ', $request->all());

        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        try {
            $appointmentId = $request->input('appointment_id');
            $feedbackRating = FeedbackRating::updateOrCreate(
                ['related_appointment_id' => $appointmentId],
                [
                    'feedback' => $request->input('feedback'),
                    'rating' => $request->input('rating')
                ]
            );

            // Update the feedback_rating column in the appointments table
            $appointment = Appointment::find($appointmentId);
            $appointment->feedback_rating = $feedbackRating->id; // Note the change here from $feedbackRating->fr_id to $feedbackRating->id
            $appointment->save();

            Log::info('Feedback and rating submitted successfully');
            Log::info('Updated feedback_rating column in appointments table with fr_id: ' . $feedbackRating->id);

            return redirect()->route('booking-details', ['appointmentId' => $appointmentId])->with('message', 'Feedback and rating submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to submit feedback and rating: ' . $e->getMessage());
            return redirect()->route('booking-details', ['appointmentId' => $request->input('appointment_id')])->with('error', 'Failed to submit feedback and rating. Please try again.');
        }
    }
}
