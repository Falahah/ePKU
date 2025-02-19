<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\FeedbackRatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminForgotPasswordController;
use App\Http\Controllers\ChangePasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/admin/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return view('welcome-admin');
})->name('admin.home');

Auth::routes();

// Allow both authenticated and guest users to access /home
Route::view('/welcome', 'welcome');
Route::get('/', [HomeController::class, 'unauthenticatedHome'])->name('unauthenticated.home')->middleware('guest');
Route::get('/home', [HomeController::class, 'authenticatedHome'])->name('home')->middleware('auth');

// Admin routes
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.submit'); // Renamed route

// Appointment routes
Route::get('/your-calendar', 'AppointmentController@showCalendar')->name('calendar');
Route::get('/check-availability/{serviceType}/{date}/{timeSlotId}', [AppointmentController::class, 'checkAvailability']);
Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create')->middleware('auth');
Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store')->middleware('auth');
Route::get('/thankyou/{appointmentId}', [ThankYouController::class, 'show'])->name('thankyou');

Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
Route::post('/update-phone', [UserController::class, 'updatePhone'])->name('update-phone');
Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('change-password');
Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('update-password');

Route::get('/booking-history', [AppointmentController::class, 'bookingHistory'])->name('booking-history');
Route::post('/cancel-appointment/{appointmentId}', [AppointmentController::class, 'cancelAppointment'])->name('cancel-appointment');
Route::view('/cancellation-confirmed', 'cancellationConfirmed')->name('cancellationConfirmed');

Route::get('/feedback-rating/create/{appointmentId}', [FeedbackRatingController::class, 'create'])->name('feedback_rating.create');
Route::post('/feedback-rating/store', [FeedbackRatingController::class, 'store'])->name('feedback_rating.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/booking-details/{appointmentId}', [AppointmentController::class, 'bookingDetails'])->name('booking-details');
});

Route::get('/appointments/{timeSlotId}', [AppointmentController::class, 'getAppointmentDetails']);

Route::get('admin/test-chart', function () {
    return view('/admin/test');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {    
    Route::get('/admin/notifications', [AdminController::class, 'fetchNotifications'])->name('admin.fetchNotifications');
    Route::post('/admin/notifications/read', [AdminController::class, 'markNotificationsAsRead'])->name('admin.markNotificationsAsRead');

    Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
                
    Route::get('/manage-appointments', [AdminController::class, 'manageAppointments'])->name('admin.manageAppointments');
    Route::get('/booking-details/{appointmentId}', [AdminController::class, 'bookingDetails'])->name('admin.bookingDetails');
    //Route::get('/admin/booking-details/{appointmentId}', [AdminController::class, 'showAppointmentDetails'])->name('admin.booking-details');
    Route::post('/admin/assign-med-staff/{appointmentId}', [AdminController::class, 'assignMedStaff'])->name('admin.assignMedStaff');
    
    Route::get('/admin/manageTimeSlots', [AdminController::class, 'manageTimeSlots'])->name('admin.manageTimeSlots');
    
    Route::get('/manage-medical-staff', [AdminController::class, 'manageMedStaff'])->name('admin.manageMedStaff');
    Route::get('/admin/medStaffDetails/{medStaffId}', [AdminController::class, 'viewMedStaffDetails'])->name('admin.medStaffDetails');
    Route::get('/admin/medStaff/{medStaffId}/edit', [AdminController::class, 'editMedStaff'])->name('admin.editMedStaff');
    Route::put('/admin/med-staff/{medStaffId}', [AdminController::class, 'updateMedStaff'])->name('admin.updateMedStaff');
    Route::delete('/admin/medStaff/{medStaffId}', [AdminController::class, 'deleteMedStaff'])->name('admin.deleteMedStaff');
    Route::get('/admin/add-medical-staff', [AdminController::class, 'addMedStaffForm'])->name('admin.addMedStaffForm');
    Route::post('/add-med-staff', [AdminController::class, 'addMedStaff'])->name('addMedStaff');
    Route::get('/admin/medStaffDetails/{medStaffId}', [AdminController::class, 'viewMedStaffDetails'])->name('admin.medStaffDetails');
    
    Route::get('/manage-feedbackrating', [AdminController::class, 'manageFeedbackRating'])->name('admin.manageFeedbackRating');

    Route::get('/manage-medical-services', [AdminController::class, 'manageMedServices'])->name('admin.manageMedServices');
    Route::post('/update-service-hours', [AdminController::class, 'updateServiceHours'])->name('admin.updateServiceHours');
    Route::get('/appointments/{timeSlotId}', [AppointmentController::class, 'getAppointmentDetails']);
        
    Route::get('/manage-announcements', [AdminController::class, 'manageAnnouncements'])->name('admin.manageAnnouncements');    Route::get('/view-announcements', [AdminController::class, 'viewAnnouncements'])->name('admin.viewAnnouncements');
    Route::post('/add-announcement', [AdminController::class, 'addAnnouncement'])->name('admin.addAnnouncement');
    Route::get('/view-announcement-details/{announcement}', [AdminController::class, 'viewAnnouncementDetails'])->name('admin.viewAnnouncementDetails');
    Route::get('/edit-announcement/{announcementId}', [AdminController::class, 'editAnnouncement'])->name('admin.editAnnouncement');
    Route::put('/update-announcement/{announcementId}', [AdminController::class, 'updateAnnouncement'])->name('admin.updateAnnouncement');
    Route::delete('/delete-announcement/{announcementId}', [AdminController::class, 'deleteAnnouncement'])->name('admin.deleteAnnouncement');

    Route::get('/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manageUsers');
    Route::get('/view-all-users', [AdminController::class, 'viewAllUsers'])->name('admin.viewAllUsers');
    Route::get('/user-details/{userId}', [AdminController::class, 'userDetails'])->name('admin.userDetails');
    Route::get('/add-user', [AdminController::class, 'addUserForm'])->name('admin.addUserForm');
    Route::post('/admin/add-user', [AdminController::class, 'addUser'])->name('admin.addUser');
    Route::post('/admin/update-user/{userId}', 'AdminController@updateUserDetails')->name('admin.updateUser');
});

Route::get('/user-table', function () {
    return view('partials.user-table');
})->name('user.table');

Route::get('/user-details/{userId}', [UserController::class, 'showDetails'])->name('user.details');
Route::delete('/user/{userId}', [UserController::class, 'delete'])->name('user.delete');

Route::prefix('user')->middleware(['auth'])->group(function () {

    Route::get('/edit/{userId}', [UserController::class, 'edit'])->name('user.edit');

    Route::put('/update/{userId}', [UserController::class, 'update'])->name('user.update');
});
