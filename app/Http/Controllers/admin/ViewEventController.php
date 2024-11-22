<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Event_schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ViewEventController extends Controller
{
    public function index($id)
    {
        $data = Event::findOrFail($id);
        $start_date = Carbon::parse($data->start_date)->format('d/m/Y');
        $end_date = Carbon::parse($data->start_date)->format('d/m/Y');
        $start_time = Carbon::parse($data->start_time)->format('H:i A');
        $end_time = Carbon::parse($data->end_time)->format('H:i A');

        $schedule = Event_schedule::where('event_guid', $id)->get();
        return view('admin.view-event.index', compact(
            'data',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'schedule',
        ));
    }
}
