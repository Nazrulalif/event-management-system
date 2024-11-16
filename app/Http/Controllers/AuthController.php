<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
