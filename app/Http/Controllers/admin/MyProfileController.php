<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function index()
    {
        return view('admin.my-profile.index');
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

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
}
