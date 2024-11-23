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
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_approve == "Y") {
                if ($user->is_active == "Y") {
                    if ($user->role_guid == 1) {
                        return redirect()->intended(route('dashboard.admin'))->with("success", "Authentication success");
                    } else {

                        return redirect()->intended(route('home'))->with("success", "Authentication success");
                    }
                } else {
                    Auth::logout();
                    return redirect(route('login'))->with("error", "Authentication failed, your account not exist in this system.");
                }
            } else {
                Auth::logout();
                return redirect(route('login'))->with("error", "Authentication failed, your account has not been approved yet.");
            }
        }

        return redirect(route('login'))->with("error", "Authentication failed, the details you entered are incorrect.");
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'team' => 'required',
            'gender' => 'required',
            'password' => 'required|string|min:8|confirmed', // Use 'confirmed' to check against 'password_confirmation'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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
