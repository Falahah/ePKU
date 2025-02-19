<?php

namespace App\Http\Controllers;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function authenticatedHome()
    {
        $user = Auth::user();
        
        if ($user->is_admin) {
            return redirect()->route('admin.home'); // Redirect admin to admin.home
        }
    
        // Fetch announcements
        $announcements = Announcement::all();
        
        return view('home', ['user' => $user, 'announcements' => $announcements]);
    }
     

    public function unauthenticatedHome()
    {
        // Fetch announcements
        $announcements = Announcement::all();
    
        if (Auth::check() && Auth::user()->is_admin) {
            return view('admin.home'); // Admin unauthenticated home
        }
    
        return view('welcome', ['announcements' => $announcements]);
    }
    
    
}
