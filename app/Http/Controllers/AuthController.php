<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function post(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Try authenticating as staff/admin
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_approve !== "Y") {
                Auth::logout();
                return redirect()->route('login')
                    ->with("error", "Your account has not been approved yet.");
            }

            if ($user->is_active !== "Y") {
                Auth::logout();
                return redirect()->route('login')
                    ->with("error", "Your account is inactive.");
            }

            // Role-based redirection
            return match ($user->role_guid) {
                1 => redirect()->intended(route('dashboard.admin'))
                    ->with("success", "Authentication successful"),
                2 => redirect()->intended(route('home'))
                    ->with("success", "Authentication successful"),
                default => redirect()->route('login')
                    ->with("error", "Invalid role assignment."),
            };
        }

        // Try authenticating as agent
        if (Auth::guard('agent')->attempt($credentials)) {
            return redirect()->intended(route('home.agent'))
                ->with("success", "Authentication successful");
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Authentication failed
        return redirect()->route('login')
            ->with("error", "Authentication failed, the details you entered are incorrect.");
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'team' => 'required',
            'gender' => 'required',
            // 'password' => 'required|string|min:8|confirmed', // Use 'confirmed' to check against 'password_confirmation'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                // 'password' => Hash::make($request->password),
                'password' => '123',
                'gender' => $request->gender,
                'team' => $request->team,
                'is_active' => "N",
                'role_guid' => "2",
                'is_approve' => "N",
            ]);

            return redirect()->route('login')->with('success', 'Your account has been successfully registered. Once the administrator approves, you can log in');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Your account failed to register.');
        }
    }
}
