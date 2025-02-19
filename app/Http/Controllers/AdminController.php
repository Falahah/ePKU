<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Announcement; 
use App\Models\Appointment;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\Department;
use App\Models\MedicalStaff;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use App\Models\Notification;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalUsers = User::count();
        $totalAppointments = Appointment::count();
        $totalServices = Service::count();
        $totalAnnouncements = Announcement::count();
    
        $appointmentsByService = Appointment::select('selected_service_type as service', DB::raw('count(*) as count'))
                                             ->groupBy('selected_service_type')
                                             ->get();
    
        $appointmentsByStatus = Appointment::select('appointment_status as status', DB::raw('count(*) as count'))
                                             ->groupBy('appointment_status')
                                             ->get();
    
        $appointmentsByMonth = Appointment::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as count'))
                                          ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                                          ->get();
    
        $servicesByAppointment = Service::withCount('appointments')
                                        ->orderBy('appointments_count', 'desc')
                                        ->get();
    
        $appointmentsTrend = Appointment::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                                        ->groupBy(DB::raw('DATE(created_at)'))
                                        ->orderBy('date', 'asc')
                                        ->get();
                                        
        $appointmentsByServiceToday = Appointment::whereDate('created_at', Carbon::today())
                                        ->select('selected_service_type', DB::raw('count(*) as count'))
                                        ->groupBy('selected_service_type')
                                        ->pluck('count', 'selected_service_type');                                        
    
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAppointments', 
            'totalServices', 
            'totalAnnouncements', 
            'appointmentsByService', 
            'appointmentsByStatus', 
            'appointmentsByMonth',
            'servicesByAppointment',
            'appointmentsTrend',
            'appointmentsByServiceToday'
        ));
    }
        
    public function dashboard()
    {
        // Total appointments by each service
        $appointmentsByService = Appointment::select('selected_service_type', DB::raw('count(*) as total'))
            ->groupBy('selected_service_type')
            ->get();

        // Total appointments by status
        $appointmentsByStatus = Appointment::select('appointment_status', DB::raw('count(*) as total'))
            ->groupBy('appointment_status')
            ->get();

        // Total number of users by role
        $usersByRole = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        // Pass the collected data to the view
        return view('admin.dashboard', compact('appointmentsByService', 'appointmentsByStatus', 'usersByRole'));
    }

    public function showProfile()
    {
        $user = auth()->user();
        $editable = true; // or false based on your logic
        return view('admin.profile', compact('user', 'editable'));
    }

    public function fetchNotifications()
    {
        $notifications = Notification::where('read', false)->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }


    public function markNotificationsAsRead(Request $request)
    {
        if ($request->has('id')) {
            // Mark a single notification as read
            $notification = Notification::find($request->id);
            $notification->read = true;
            $notification->save();
        } else {
            // Mark all notifications as read
            Notification::where('read', false)->update(['read' => true]);
        }

        return response()->json(['message' => 'Notifications marked as read']);
    }
        

    public function manageUsers()
    {
        $adminUsers = User::where('role', 'admin')->get();
        $studentUsers = User::where('role', 'student')->get();
        $staffUsers = User::where('role', 'staff')->get();
    
        $tab = "admin";
        return view('admin.manageUsers', compact('adminUsers', 'studentUsers', 'staffUsers', 'tab'));
    }

    public function addUserForm()
    {
        return view('admin.addUser');
    }

    public function addUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'IC' => 'required|string|size:12',
            'gender' => 'required|in:male,female',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:admin,student,staff',
        ]);
    
        // Hash the password
        $validatedData['password'] = Hash::make($validatedData['password']);
    
        // Create and save the new user
        $user = User::create($validatedData);
    
        // Redirect to the userDetails route of the added user
        return redirect()->route('admin.userDetails', ['userId' => $user->userID])->with('success', 'User added successfully!');
    }
    
    public function viewAllUsers()
    {
        // Fetch users of all roles from the database
        $adminUsers = User::where('role', 'admin')->get();
        $studentUsers = User::where('role', 'student')->get();
        $staffUsers = User::where('role', 'staff')->get();
    
        // Define the tab variable
        $tab = "admin"; // You can set the default tab here
    
        // Pass the users data and the tab variable to the view
        return view('admin.viewAllUsers', compact('adminUsers', 'studentUsers', 'staffUsers', 'tab'));
    }

    public function userDetails($userId)
    {
        // Your logic to retrieve and display user details goes here
        $user = User::find($userId);
        
        return view('admin.userDetails', ['user' => $user]);
    }    
    
    public function manageAppointments(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to manage appointments.');
        }
    
        Appointment::where('appointment_status', '!=', 'cancelled')
            ->where('date', '<', now()->toDateString())
            ->orWhere(function($query) {
                $query->where('date', '=', now()->toDateString())
                    ->whereHas('timeSlot', function($query) {
                        $query->where('start_time', '<', now()->toTimeString());
                    });
            })
        ->update(['appointment_status' => 'completed']);
    
        $filterDate = $request->input('filter_date', 'earliest');
        $filterService = $request->input('filter_service', null);
        $filterAssigned = $request->input('filter_assigned', null);
    
        $upcomingAppointmentsQuery = Appointment::where('appointment_status', '!=', 'cancelled')
            ->where(function($query) {
                $query->where('date', '>', now()->toDateString())
                    ->orWhere(function($query) {
                        $query->where('date', '=', now()->toDateString())
                            ->whereHas('timeSlot', function($query) {
                                $query->where('start_time', '>', now()->toTimeString());
                            });
                    });
            });
    
        $completedAppointmentsQuery = Appointment::where('appointment_status', 'completed');
    
        $cancelledAppointmentsQuery = Appointment::where('appointment_status', 'cancelled');
    
        if ($filterService) {
            $upcomingAppointmentsQuery->where('selected_service_type', $filterService);
            $completedAppointmentsQuery->where('selected_service_type', $filterService);
            $cancelledAppointmentsQuery->where('selected_service_type', $filterService);
        }
    
        if ($filterAssigned !== null) {
            if ($filterAssigned === 'assigned') {
                $upcomingAppointmentsQuery->whereNotNull('msID');
                $completedAppointmentsQuery->whereNotNull('msID');
                $cancelledAppointmentsQuery->whereNotNull('msID');
            } else {
                $upcomingAppointmentsQuery->whereNull('msID');
                $completedAppointmentsQuery->whereNull('msID');
                $cancelledAppointmentsQuery->whereNull('msID');
            }
        }
    
        $orderBy = $filterDate === 'earliest' ? 'asc' : 'desc';
        $upcomingAppointmentsQuery->orderBy('date', $orderBy)
            ->orderBy(function ($query) {
                $query->from('time_slots')
                    ->select('start_time')
                    ->whereColumn('time_slots.time_id', 'appointments.selected_time_slot');
            }, $orderBy);
        $completedAppointmentsQuery->orderBy('date', $orderBy)
            ->orderBy(function ($query) {
                $query->from('time_slots')
                    ->select('start_time')
                    ->whereColumn('time_slots.time_id', 'appointments.selected_time_slot');
            }, $orderBy);
        $cancelledAppointmentsQuery->orderBy('date', $orderBy)
            ->orderBy(function ($query) {
                $query->from('time_slots')
                    ->select('start_time')
                    ->whereColumn('time_slots.time_id', 'appointments.selected_time_slot');
            }, $orderBy);
    
        $upcomingAppointments = $upcomingAppointmentsQuery->paginate(10, ['*'], 'upcoming');
        $completedAppointments = $completedAppointmentsQuery->paginate(10, ['*'], 'completed');
        $cancelledAppointments = $cancelledAppointmentsQuery->paginate(10, ['*'], 'cancelled');
    
        $services = Service::all();
    
        return view('admin.manageAppointments', compact('upcomingAppointments', 'completedAppointments', 'cancelledAppointments', 'filterDate', 'filterService', 'filterAssigned', 'services'));
    }
    
    public function bookingDetails($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
    
        $medicalStaff = MedicalStaff::where('active', true)->get();     
        return view('admin.booking-details', compact('appointment', 'medicalStaff'));
    }
    
    public function assignMedStaff(Request $request, $appointmentId)
    {
        $validatedData = $request->validate([
            'msID' => 'required|exists:medical_staff,msID',
        ]);
    
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->msID = $validatedData['msID'];
        $appointment->save();
    
        return redirect()->route('admin.bookingDetails', ['appointmentId' => $appointmentId])
                         ->with('message', 'Medical staff assigned successfully!');
    }    
    
    public function manageTimeSlots(Request $request)
    {
        $services = Service::all();
        $timeSlots = [];
        $bookedTimeSlots = [];
    
        if ($request->has('date') && $request->has('service')) {
            $selectedDate = $request->input('date');
            $selectedService = $request->input('service');
    
            $bookedTimeSlots = Appointment::where('date', $selectedDate)
                ->where('selected_service_type', $selectedService)
                ->pluck('selected_time_slot')
                ->toArray();
    
            $timeSlots = TimeSlot::all();
        }
    
        return view('admin.manageTimeSlots', compact('services', 'timeSlots', 'bookedTimeSlots'));
    }

    public function getAppointmentDetails($timeSlotId)
    {
        \Log::info("Fetching appointment details for time slot ID: $timeSlotId");

        $appointment = Appointment::where('selected_time_slot', $timeSlotId)->with('user', 'timeSlot')->first();

        if (!$appointment) {
            \Log::error("Appointment not found for time slot ID: $timeSlotId");
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        \Log::info("Appointment found: ", $appointment->toArray());

        return response()->json([
            'service' => $appointment->selected_service_type,
            'date' => $appointment->date,
            'time_slot' => \Carbon\Carbon::parse($appointment->timeSlot->start_time)->format('h:i A'),
            'booked_by' => $appointment->user->name,
            'status' => $appointment->appointment_status,
        ]);
    }    
    
    public function manageMedStaff()
    {
        $departments = Department::with('medicalStaff')->get();
        
        return view('admin.manageMedStaff', compact('departments'));
    }
    
    public function viewMedStaffDetails($medStaff)
    {
        $medStaff = MedicalStaff::findOrFail($medStaff);
        
        return view('admin.medStaffDetails', compact('medStaff'));
    }
    
    public function editMedStaff($medStaff)
    {
        $medStaff = MedicalStaff::with('department', 'unit')->findOrFail($medStaff);
        $departments = Department::all();
        $units = Unit::all();
    
        return view('admin.med-staff-edit', compact('medStaff', 'departments', 'units'));
    }
    
    public function updateMedStaff(Request $request, $medStaff)
    {
        $medStaff = MedicalStaff::findOrFail($medStaff);
    
        // Validate the input, including department_id and unit_id as required fields
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,department_id',
            'unit_id' => 'nullable|exists:units,unit_id',
            'active' => 'required|boolean',
        ]);
    
        // Handle the "No Associated Unit" option
        if ($request->unit_id === '') {
            $validatedData['unit_id'] = null;
        }
    
        // Update medical staff with validated data
        $medStaff->update($validatedData);
    
        // Redirect to the manage medical staff page with a success message
        return redirect()
            ->route('admin.medStaffDetails', ['medStaffId' => $medStaff->msID])
            ->with('success', 'The details updated successfully!');
    }
    
    
    public function deleteMedStaff($medStaff)
    {
        // Find the medical staff member by ID
        $medStaff = MedicalStaff::findOrFail($medStaff);

        // Delete the staff member
        $medStaff->delete();

        // Redirect back to the page with success message
        return redirect()->route('admin.manageMedStaff')->with('success', 'Staff member deleted successfully!');
    }

    public function addMedStaffForm()
    {
        $departments = Department::all();
        $units = Unit::all();
        return view('admin.addMedStaff', compact('departments', 'units'));
    }

    public function addMedStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required|string',
            'department_id' => 'required|exists:departments,department_id',
            'unit_id' => 'nullable|exists:units,unit_id',
        ]);

        $medicalStaff = MedicalStaff::create([
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'department_id' => $request->input('department_id'),
            'unit_id' => $request->input('unit_id'),
        ]);

        return redirect()->route('admin.medStaffDetails', ['medStaffId' => $medicalStaff->msID])->with('success', 'Medical Staff added successfully!');
    }

    public function manageMedServices()
    {
        $services = Service::all();
    
        return view('admin.manageMedServices', compact('services'));
    }
    
    public function updateServiceHours(Request $request)
    {
        $service = Service::findOrFail($request->input('service_id'));
    
        $service->sunday_opening = $request->input('sunday_opening');
        $service->sunday_closing = $request->input('sunday_closing');
        $service->monday_opening = $request->input('monday_opening');
        $service->monday_closing = $request->input('monday_closing');
        $service->tuesday_opening = $request->input('tuesday_opening');
        $service->tuesday_closing = $request->input('tuesday_closing');
        $service->wednesday_opening = $request->input('wednesday_opening');
        $service->wednesday_closing = $request->input('wednesday_closing');
        $service->thursday_opening = $request->input('thursday_opening');
        $service->thursday_closing = $request->input('thursday_closing');
    
        $service->save();
    
        return response()->json(['message' => 'Service hours updated successfully']);
    }    
    
    public function manageAnnouncements()
    {
        $announcements = Announcement::all();
        
        return view('admin.manageAnnouncements', ['announcements' => $announcements]);    
    }
    
    public function showAnnouncements()
    {
        $announcements = Announcement::all();
        return view('partials._announcement', ['announcements' => $announcements]);
    }

    public function viewAnnouncementDetails($announcementId)
    {
        $announcement = Announcement::findOrFail($announcementId);

        return view('admin.announcementDetails', ['announcement' => $announcement]);
    }    

    public function editAnnouncement($announcementId)
    {
        $announcement = Announcement::find($announcementId);
        if (!$announcement) {
            // Handle case where announcement is not found
        }
        return view('partials.edit-announcement', ['announcement' => $announcement]);
    }

    public function updateAnnouncement(Request $request, $announcementId)
    {
        // Find the announcement by its ID
        $announcement = Announcement::findOrFail($announcementId);
    
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|boolean', // Added validation for visibility
        ]);
    
        // Update the announcement with the validated data
        $announcement->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'visible' => $validatedData['visibility'], // Update visibility
        ]);
        // Redirect back to the manageAnnouncements route with success message
        return redirect()
        ->route('admin.manageAnnouncements', ['announcementId' => $announcement->announcement_id])
        ->with('success', 'Announcement updated successfully!');
    }    

    public function deleteAnnouncement($announcementId)
    {
        // Logic to delete the announcement
        $announcement = Announcement::findOrFail($announcementId);
        $announcement->delete();

        // Redirect back with success message
        return redirect()->route('admin.manageAnnouncements')->with('success', 'Announcement deleted successfully!');
    }

    public function addAnnouncementForm()
    {
        return view('admin.add-announcement-form');
    }

    public function addAnnouncement(Request $request)
    {
        // Validation rules for the announcement
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    
        // Create the announcement
        Announcement::create([
            'user_id' => auth()->id(), // Assuming the authenticated user is adding the announcement
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
        ]);
    
        // Redirect back with success message
        return redirect()->route('admin.manageAnnouncements')->with('success', 'Announcement added successfully!');;    
    }    
}
