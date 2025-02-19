<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User; // Make sure to import the correct namespace for the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = auth()->user();
        $editable = true;
    return view('profile', compact('user', 'editable'));
    }

    public function editPhoneNumber()
    {
        $user = auth()->user();
        $user->phone_number = request('phone_number');
        $user->save();

        return redirect()->route('profile');
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits_between:10,15',
        ]);
    
        $user = Auth::user();
        $user->phone_number = $request->phone_number;
    
        if ($user->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }    

    public function showDetails($userId)
    {
        $user = User::find($userId);
        
        return view('admin.userDetails', ['user' => $user]);
    }

    public function edit($userId)
    {
        $user = User::find($userId);
    
        return view('user-edit', compact('user'));
    }
    
    public function update(Request $request, $userId)
    {
        $user = User::find($userId);
    
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'IC' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:female,male',
            'email' => 'required|email|unique:users,email,' . $user->userID . ',userID',
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:student,staff,admin',
            // Add more validation rules for other fields as needed
        ]);
    
        // Update all the user details
        $user->update($validatedData);
    
        // Redirect back to the form with a success message
        return redirect()
        ->route('user.details', ['userId' => $user->userID])
        ->with('success', 'User details updated successfully!');
    }
    

    // In your UserController
    public function delete($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Delete the user
        $user->delete();

        return redirect()->route('admin.manageUsers')->with('success', 'User deleted successfully.');
    }

    public function updateUser($userId, Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'IC' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'email' => 'required|email|unique:users,email,'.$userId,
            'phone' => 'required|string|max:255',
        ]);

        // Find the user by ID
        $user = User::findOrFail($userId);

        // Update user details
        $user->name = $request->input('name');
        $user->IC = $request->input('IC');
        $user->date_of_birth = $request->input('dob');
        $user->gender = $request->input('gender');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone');

        // Save the updated user
        $user->save();

        // Optionally, you can return a response indicating success
        return response()->json(['message' => 'User details updated successfully'], 200);
    }

}
