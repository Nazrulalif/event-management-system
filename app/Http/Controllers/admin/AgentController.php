<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Agent::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->editColumn('created_at', function ($user) {
                    return Carbon::parse($user->created_at)->format('d/m/Y');
                })
                ->make(true);
        }

        return view('admin.agent.index');
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'channel' => 'required',
            // Add conditional validation for dynamic fields
            'company_name' => 'required_if:channel,Rover',
            'unit' => 'required_if:channel,Nextstar',
        ]);

        try {
            Agent::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone, // Corrected key to match input name
                'channel' => $request->channel,
                'attribute' => $request->channel === 'Rover' ? $request->company_name : ($request->channel === 'Nextstar' ? $request->unit : ''),
            ]);

            return redirect()->back()->with('success', "Agent added successfully.");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Agent addition failed.");
        }
    }


    public function delete($id)
    {
        $agent = Agent::find($id);
        if ($agent) {
            $agent->delete();
            return response()->json(['message' => 'Agent deleted successfully']);
        }
        return response()->json(['message' => 'Agent not found'], 404);
    }
}