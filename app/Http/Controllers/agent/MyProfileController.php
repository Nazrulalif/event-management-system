<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller
{
    public function index()
    {
        return view('agent.my-profile.detail');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'channel' => 'required',
            'company_name' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('agent')->user()->id;
        $agent = Agent::findOrFail($user);
        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->phone_number = $request->phone;
        $agent->channel = $request->channel;

        // Save the attribute based on the selected channel
        if ($request->channel === 'Rovers') {
            $agent->attribute = $request->company_name; // Use company_name for 'Rovers'
        } elseif ($request->channel === 'Nextstar') {
            $agent->attribute = $request->unit; // Use unit for 'Nextstar'
        } else {
            $agent->attribute = null; // Clear attribute for other channels
        }

        $agent->save();

        return redirect()->back()->with('success', 'Agent details updated successfully.');
    }

    public function change_password(Request $request)
    {
        // dd($request->all());

        // Validation rules with confirmation
        $request->validate([
            'password' => 'required|string|min:8|confirmed', // Use 'confirmed' to check against 'password_confirmation'
        ]);

        $userId = Auth::guard('agent')->user()->id;
        $user = Agent::findOrFail($userId);

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Your password has been changed successfully.');
    }
}
