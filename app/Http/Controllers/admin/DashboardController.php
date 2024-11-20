<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;

        // Get monthly counts for each status
        $monthlyData = Event::where('status', '!=', 'Draft')
            ->whereYear('start_date', $currentYear)
            ->selectRaw('MONTH(start_date) as month')
            ->selectRaw('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('month', 'status')
            ->get();

        // Initialize arrays for each status with zeros for all months
        $approved = array_fill(1, 12, 0);
        $pending = array_fill(1, 12, 0);
        $rejected = array_fill(1, 12, 0);

        // Fill in the actual counts
        foreach ($monthlyData as $data) {
            switch ($data->status) {
                case 'Approve':
                    $approved[$data->month] = $data->count;
                    break;
                case 'Pending':
                    $pending[$data->month] = $data->count;
                    break;
                case 'Reject':
                    $rejected[$data->month] = $data->count;
                    break;
            }
        }

        // Get totals for summary
        $totalEvent = Event::where('status', '!=', 'Draft')->count();
        $totalEventApprove = Event::where('status', 'Approve')->count();
        $totalEventPending = Event::where('status', 'Pending')->count();
        $totalEventReject = Event::where('status', 'Reject')->count();

        return view('admin.dashboard.index', compact(
            'totalEvent',
            'totalEventApprove',
            'totalEventPending',
            'totalEventReject',
            'approved',
            'pending',
            'rejected',
            'currentYear'
        ));
    }
}
