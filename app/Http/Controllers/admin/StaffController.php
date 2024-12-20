<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*', 'users.id as id', 'users.created_at as created_at')
                ->join('roles', 'roles.id', '=', 'users.role_guid')
                ->where('users.is_approve', 'Y')
                ->where('users.is_active', 'Y')
                ->where('users.id', '!=', Auth::user()->id)
                ->orderBy('users.created_at', 'desc')
                ->get();
            return DataTables::of($data)
                ->editColumn('created_at', function ($user) {
                    return Carbon::parse($user->created_at)->format('d/m/Y');
                })
                ->make(true);
        }

        $pendingCount = User::where('is_approve', 'N')->where('is_active', 'N')->count();
        return view('admin.staff.active', compact('pendingCount'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'access' => 'required',
            'gender' => 'required',
            'team' => 'required',
        ]);

        $generatedPassword = Str::random(10);

        try {
            $user = User::create([
                'name' => $request->name,
                'gender' => $request->gender,
                'email' => $request->email,
                'role_guid' => $request->access,
                'password' => Hash::make($generatedPassword),
                'is_active' => "Y",
                'is_approve' => "Y",
                'team' => $request->team,
            ]);

            // Send the welcome email
            Mail::to($user->email)->queue(new WelcomeEmail($user, $generatedPassword));


            return redirect()->back()->with('success', "User registration success.");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "User registration failed.");
        }
    }

    public function deactivate($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_active = 'N';
            $user->save();

            return response()->json(['message' => 'User deactivated successfully'], 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function pending(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*')
                ->where('is_approve', 'N')
                ->where('is_active', 'N')
                ->where('id', '!=', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return DataTables::of($data)
                ->editColumn('created_at', function ($user) {
                    return Carbon::parse($user->created_at)->format('d/m/Y');
                })
                ->make(true);
        }
        $pendingCount = User::where('is_approve', 'N')->where('is_active', 'N')->count();

        return view('admin.staff.pending', compact('pendingCount'));
    }

    public function accept($id)
    {
        $user = User::find($id);
        if ($user) {
            $newPassword = Str::random(8);
            $user->is_active = 'Y';
            $user->is_approve = 'Y';
            $user->password = Hash::make($newPassword);
            $user->save();

            Mail::to($user->email)->queue(new WelcomeEmail($user, $newPassword));
            return response()->json(['message' => 'User approved successfully'], 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function reject($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete(); // Delete the user request
            return response()->json(['message' => 'User request deleted successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);
        return view('admin.staff.detail', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'team' => $request->team,
                'unit' => $request->unit,
                'state' => $request->state,
            ]);
            return redirect()->back()->with('success', 'User detail updated Successfully.');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'User detail update failed.');
        }
    }
}
