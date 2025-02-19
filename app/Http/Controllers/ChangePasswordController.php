<?php
// app/Http/Controllers/ChangePasswordController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ]);
    
        $user = Auth::user();
    
        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
    
            return redirect()->route('profile')->with('success', 'Password changed successfully!');
        }
    
        return back()->withErrors(['current_password' => 'Incorrect current password.']);
    }
            
}
