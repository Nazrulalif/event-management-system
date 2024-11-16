<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::select('events.*', 'users.name as creator_name', 'events.id as id')  // Select event data along with the user's name
                ->join('users', 'users.id', '=', 'events.created_by')
                ->where('events.status', '!=', 'Draft')
                ->where('events.status', '!=', 'Pending')
                ->orderBy('updated_at', 'desc')
                ->get();

            return DataTables::of($data)
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d/m/Y');
                })
                ->editColumn('start_date', function ($data) {
                    return Carbon::parse($data->start_date)->format('d/m/Y');
                })
                ->editColumn('end_date', function ($data) {
                    return Carbon::parse($data->end_date)->format('d/m/Y');
                })
                ->make(true);
        }

        $pendingCount = Event::where('status', 'Pending')->count();

        return view('admin.event.all', compact('pendingCount'));
    }

    public function delete($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete(); // Delete the user request
            return response()->json(['message' => 'Event deleted successfully']);
        }
        return response()->json(['message' => 'Event not found'], 404);
    }

    public function pending(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::select('events.*', 'users.name as creator_name', 'events.id as id')  // Select event data along with the user's name
                ->join('users', 'users.id', '=', 'events.created_by')
                ->where('events.status', 'Pending')
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($data)
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d/m/Y');
                })
                ->editColumn('start_date', function ($data) {
                    return Carbon::parse($data->start_date)->format('d/m/Y');
                })
                ->editColumn('end_date', function ($data) {
                    return Carbon::parse($data->end_date)->format('d/m/Y');
                })
                ->make(true);
        }

        $pendingCount = Event::where('status', 'Pending')->count();

        return view('admin.event.pending', compact('pendingCount'));
    }

    public function approve($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->status = 'Approve';
            $event->save();
            return response()->json(['message' => 'Event request approved successfully']);
        }
        return response()->json(['message' => 'Event not found'], 404);
    }

    public function reject($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->status = 'Reject';
            $event->save();
            return response()->json(['message' => 'Event request rejected successfully']);
        }
        return response()->json(['message' => 'Event not found'], 404);
    }

    public function draft(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::select('*')
                ->where('status', 'draft')
                ->where('created_by', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($data)
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d/m/Y');
                })
                ->editColumn('start_date', function ($data) {
                    return Carbon::parse($data->start_date)->format('d/m/Y');
                })
                ->editColumn('end_date', function ($data) {
                    return Carbon::parse($data->end_date)->format('d/m/Y');
                })
                ->make(true);
        }

        $pendingCount = Event::where('status', 'Pending')->count();
        return view('admin.event.draft', compact('pendingCount'));
    }

    public function detail($id)
    {
        return view('admin.event.detail');
    }
}
