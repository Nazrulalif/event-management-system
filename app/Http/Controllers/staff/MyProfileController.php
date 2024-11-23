<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MyProfileController extends Controller
{
    public function index()
    {
        return view('staff.my-profile.index');
    }

    public function update(Request $request)
    {

        $request->validate([
            'profile_picture' => 'nullable',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        if ($request->hasFile('profile_picture')) {
            // Handle profile picture upload
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture); // Delete old picture from SFTP
            }

            // Upload the new profile picture
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;

            // Store the file on the SFTP server
            $filePath = 'profile-picture/' . $uniqueFileName;
            Storage::put($filePath, file_get_contents($file));
            $user->profile_picture = $filePath;
            $user->update([
                'profile_picture' => $filePath,
            ]);
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'team' => $request->team,
                'unit' => $request->unit,
                'state' => $request->state,
            ]);
            return redirect()->back()->with('success', 'Your profile detail updated Successfully.');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Your profile detail update failed.');
        }
    }

    public function change_password(Request $request)
    {
        // dd($request->all());

        // Validation rules with confirmation
        $request->validate([
            'password' => 'required|string|min:8|confirmed', // Use 'confirmed' to check against 'password_confirmation'
        ]);

        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Your password has been changed successfully.');
    }
}
