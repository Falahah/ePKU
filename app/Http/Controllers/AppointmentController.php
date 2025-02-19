<?php

// app/Http/Controllers/AppointmentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\Appointment;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentCancellation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Events\AppointmentCancelled;
use App\Events\AppointmentBooked;

class AppointmentController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $services = Service::all();
        
        // Check if it's past 4 pm for today
        $disableDate = now()->hour >= 19;
    
        // Get the booked time slots for the selected date
        $bookedTimeSlots = Appointment::where('date', now()->toDateString())->pluck('selected_time_slot');
    
        // Initialize $cancelledTimeSlots
        $cancelledTimeSlots = [];
    
        // Get service time restrictions
        $serviceTimeRestrictions = [];
        foreach ($services as $service) {
            $serviceTimeRestrictions[$service->service_type] = [
                '0' => $service->sunday_closing,
                '1' => $service->monday_closing,
                '2' => $service->tuesday_closing,
                '3' => $service->wednesday_closing,
                '4' => $service->thursday_closing,
            ];
        }
    
        // Get time slots
        $timeSlots = TimeSlot::all();
    
        return view('appointments.create', compact('services', 'timeSlots', 'disableDate', 'bookedTimeSlots', 'cancelledTimeSlots', 'serviceTimeRestrictions'));
    }

    public function getAppointmentDetails($timeSlotId)
    {
        $appointment = Appointment::where('selected_time_slot', $timeSlotId)
            ->with('user', 'timeSlot')
            ->first();
    
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }
    
        return response()->json([
            'service' => $appointment->selected_service_type,
            'date' => $appointment->date,
            'time_slot' => \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('h:i A'),
            'booked_by' => $appointment->user->name,
            'status' => $appointment->appointment_status,
        ]);
    }    

    public function checkAvailability($serviceType, $date, $timeSlotId)
    {
        // Check if the time slot is available or canceled
        $isAvailable = !Appointment::where('selected_service_type', $serviceType)
            ->where('date', $date)
            ->where(function ($query) use ($timeSlotId) {
                $query->where('selected_time_slot', $timeSlotId)
                    ->orWhere(function ($query) use ($timeSlotId) {
                        $query->where('selected_time_slot', $timeSlotId)
                            ->where('appointment_status', 'cancelled');
                    });
            })
            ->exists();
    
        // Check if the time slot is canceled
        $isCancelled = Appointment::where('selected_service_type', $serviceType)
            ->where('date', $date)
            ->where('selected_time_slot', $timeSlotId)
            ->where('appointment_status', 'cancelled')
            ->exists();
    
        return response()->json(['available' => $isAvailable, 'cancelled' => $isCancelled]);
    }

    // Define a method to get service time restrictions
    private function getServiceTimeRestrictions()
    {
        // Implement your logic to retrieve service time restrictions here
        // For example:
        return [
            'Medical Checkup' => [
                0 => ['start' => '08:00', 'end' => '17:00'], // Monday
                1 => ['start' => '08:00', 'end' => '17:00'], // Tuesday
                // Add restrictions for other days
            ],
            'Dental Checkup' => [
                // Define restrictions for different days
            ],
            // Add restrictions for other services
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_service_type' => 'required',
            'selected_time_slot' => 'required',
            'date' => 'required|date',
            // Add other validation rules as needed
        ]);
    
        $user = auth()->user();
    
        // Ensure that the user is authenticated before proceeding
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to book an appointment.');
        }
    
        // Create a new appointment with the user ID
        $appointment = new Appointment([
            'user_id' => $user->userID,
            'selected_service_type' => $request->input('selected_service_type'),
            'selected_time_slot' => $request->input('selected_time_slot'),
            'date' => $request->input('date'),
            // Add other fields as needed
        ]);
    
        // Save the appointment to the database
        $saved = $appointment->save();

        event(new AppointmentBooked($appointment));
    
        if ($saved) {
            // Send confirmation email
            Mail::to($user->email)->send(new AppointmentConfirmation($appointment));
    
            // Redirect to a confirmation page with the actual appointment ID
            return redirect()->route('thankyou', ['appointmentId' => $appointment->appointment_id])->with('bookingDetails', [
                'appointment_id' => $appointment->appointment_id, // Pass the appointment ID here
                'service' => $appointment->selected_service_type,
                'date' => $appointment->date,
                'timeSlot' => $appointment->selected_time_slot,
            ]);
        } else {
            // Handle the case where the appointment was not saved
            return redirect()->route('appointments.create')->with('error', 'Failed to book the appointment. Please try again.');
        }
    }    
    
    public function bookingHistory(Request $request)
    {
        // Ensure that the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to view booking history.');
        }
    
        $user = auth()->user();
        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();
    
        // Get filter inputs
        $filterDate = $request->input('filter_date', 'earliest');
        $filterService = $request->input('filter_service', null);
    
        // Query to get upcoming appointments
        $upcomingAppointmentsQuery = Appointment::where('user_id', $user->userID)
            ->where('appointment_status', '!=', 'cancelled')
            ->where(function($query) use ($currentDate, $currentTime) {
                $query->where('date', '>', $currentDate)
                    ->orWhere(function($query) use ($currentDate, $currentTime) {
                        $query->where('date', '=', $currentDate)
                            ->whereHas('timeSlot', function($query) use ($currentTime) {
                                $query->where('start_time', '>', $currentTime);
                            });
                    });
            });
    
        // Apply service filter if selected
        if ($filterService) {
            $upcomingAppointmentsQuery->where('selected_service_type', $filterService);
        }
    
        // Apply date filter
        $upcomingAppointmentsQuery->orderBy('date', $filterDate === 'earliest' ? 'asc' : 'desc')
            ->orderBy(function ($query) {
                $query->from('time_slots')
                    ->select('start_time')
                    ->whereColumn('time_slots.time_id', 'appointments.selected_time_slot');
            }, $filterDate === 'earliest' ? 'asc' : 'desc');
    
        $upcomingAppointments = $upcomingAppointmentsQuery->paginate(10, ['*'], 'upcoming');
    
        // Query to get completed appointments
        $completedAppointmentsQuery = Appointment::where('user_id', $user->userID)
            ->where(function($query) use ($currentDate, $currentTime) {
                $query->where('appointment_status', 'completed')
                    ->orWhere(function($query) use ($currentDate, $currentTime) {
                        $query->where('date', '<', $currentDate)
                            ->orWhere(function($query) use ($currentDate, $currentTime) {
                                $query->where('date', '=', $currentDate)
                                    ->whereHas('timeSlot', function($query) use ($currentTime) {
                                        $query->where('start_time', '<=', $currentTime);
                                    });
                            });
                    });
            });
    
        // Apply service filter if selected
        if ($filterService) {
            $completedAppointmentsQuery->where('selected_service_type', $filterService);
        }
    
        // Apply date filter
        $completedAppointmentsQuery->orderBy('date', $filterDate === 'earliest' ? 'asc' : 'desc')
            ->orderBy(function ($query) {
                $query->from('time_slots')
                    ->select('start_time')
                    ->whereColumn('time_slots.time_id', 'appointments.selected_time_slot');
            }, $filterDate === 'earliest' ? 'asc' : 'desc');
    
        $completedAppointments = $completedAppointmentsQuery->paginate(10, ['*'], 'completed');
    
        // Query to get cancelled appointments
        $cancelledAppointmentsQuery = Appointment::where('user_id', $user->userID)
            ->where('appointment_status', 'cancelled');
    
        // Apply service filter if selected
        if ($filterService) {
            $cancelledAppointmentsQuery->where('selected_service_type', $filterService);
        }
    
        // Apply date filter
        $cancelledAppointmentsQuery->orderBy('date', $filterDate === 'earliest' ? 'asc' : 'desc')
            ->orderBy(function ($query) {
                $query->from('time_slots')
                    ->select('start_time')
                    ->whereColumn('time_slots.time_id', 'appointments.selected_time_slot');
            }, $filterDate === 'earliest' ? 'asc' : 'desc');
    
        $cancelledAppointments = $cancelledAppointmentsQuery->paginate(10, ['*'], 'cancelled');
    
        // Get all services for the filter dropdown
        $services = Service::all();
    
        // Pass the appointments and filter options to the view
        return view('appointments.booking-history', compact('upcomingAppointments', 'completedAppointments', 'cancelledAppointments', 'services', 'filterDate', 'filterService'));
    }
                        
    public function confirmAppointment($appointmentId)
    {
        try {
            // Logic to confirm the appointment, update the database, etc.
            $appointment = Appointment::findOrFail($appointmentId);
    
            // Update appointment status to 'completed'
            $appointment->update(['appointment_status' => 'completed']);
    
            // Send the email
            Mail::to($appointment->user->email)->send(new AppointmentConfirmation($appointment));
    
            // You can add success messages or redirect the user here
            return redirect()->route('appointments.index')->with('success', 'Appointment confirmed successfully');
        } catch (\Exception $e) {
            // Handle exceptions, log errors, or provide user-friendly messages
            // You might want to redirect the user back with an error message
            return redirect()->back()->with('error', 'Failed to send confirmation email');
        }
    }

    public function bookingDetails($appointmentId)
    {
        try {
            // Retrieve the appointment details with necessary relationships
            $appointment = Appointment::with(['user', 'service', 'timeSlot', 'feedbackRating'])->findOrFail($appointmentId);
    
            // You can customize this view according to your requirements
            return view('appointments.booking-details', compact('appointment'));
        } catch (\Exception $e) {
            // Handle exceptions, log errors, or provide user-friendly messages
            // You might want to redirect the user back with an error message
            return redirect()->back()->with('error', 'Failed to fetch booking details');
        }
    }

    public function cancelAppointment(Request $request, $appointmentId)
    {
        try {
            // Find the appointment
            $appointment = Appointment::findOrFail($appointmentId);
    
            // Ensure the user relationship is loaded
            $appointment->load('user');
    
            // Check if the user exists
            if (!$appointment->user || !$appointment->user->email) {
                return response()->json(['success' => false, 'error' => 'Failed to cancel appointment. User not found or has no email.']);
            }
    
            // Release the time slot
            $cancelledTimeSlotId = $appointment->selected_time_slot;
            $date = $appointment->date;
    
            // Update appointment status to 'cancelled'
            $appointment->update(['appointment_status' => 'cancelled']);
    
            // Get the list of booked and cancelled time slots for the selected date
            $bookedTimeSlots = Appointment::where('date', $date)->pluck('selected_time_slot');
            $cancelledTimeSlots = Appointment::where('date', $date)->where('appointment_status', 'cancelled')->pluck('selected_time_slot');
    
            // Store cancellation details in session
            Session::put('cancellationDetails', [
                'appointment_id' => $appointment->appointment_id,
                'service' => $appointment->selected_service_type,
                'date' => $appointment->date,
                'timeSlot' => $appointment->selected_time_slot,
            ]);
                // Dispatch the AppointmentCancelled event
                event(new AppointmentCancelled($appointment));
    
            // Send cancellation confirmation email
            Mail::to($appointment->user->email)->send(new AppointmentCancellation($appointment));
        
            // Redirect to cancellation confirmed view
            return redirect()->route('cancellationConfirmed');
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Cancel Appointment Error: ' . $e->getMessage());
    
            return response()->json(['success' => false, 'error' => 'Failed to cancel appointment']);
        }
    }    
    }
