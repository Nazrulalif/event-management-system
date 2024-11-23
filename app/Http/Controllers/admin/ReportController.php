<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function post(Request $request)
    {
        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Retrieve the input values
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch events within the selected date range
        $events = Event::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate]);
        })
            ->where('status', '!=', 'Draft') // Exclude Draft status globally
            ->get();

        // Calculate the status counts
        $statusCounts = [
            'approved' => $events->where('status', 'Approve')->count(),
            'pending' => $events->where('status', 'Pending')->count(),
            'rejected' => $events->where('status', 'Reject')->count(),
            'cancelled' => $events->where('status', 'Cancelled')->count(),
        ];

        // Pass data to the view
        return view('admin.report.index', compact('events', 'statusCounts', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Retrieve the input values
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch events within the selected date range
        $events = Event::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate]);
        })
            ->where('status', '!=', 'Draft') // Exclude Draft status globally
            ->get();

        // Calculate the status counts
        $statusCounts = [
            'approved' => $events->where('status', 'Approve')->count(),
            'pending' => $events->where('status', 'Pending')->count(),
            'rejected' => $events->where('status', 'Reject')->count(),
            'cancelled' => $events->where('status', 'Cancelled')->count(),
        ];

        return view('admin.report.print', compact('events', 'statusCounts', 'startDate', 'endDate'));
    }
}
