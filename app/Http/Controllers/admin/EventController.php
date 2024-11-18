<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Event_reward;
use App\Models\Event_schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function edit($id)
    {
        $data = Event::findOrFail($id);
        return view('admin.event.main', compact('data'));
    }

    public function main_update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'platform' => 'required',
            'objective' => 'required',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate poster
        ]);

        $event = Event::findOrFail($id);

        // dd($event);

        try {
            $startDate = Carbon::parse($request->start_date); // Parse the start date
            $endDate = Carbon::parse($request->end_date);
            // Calculate the difference in days
            $periodCount = $startDate->diffInDays($endDate);

            $posterPath = $event->poster_path; // Retain the current poster path by default

            // Handle new poster upload
            if ($request->hasFile('poster')) {
                $file = $request->file('poster');
                $extension = $file->getClientOriginalExtension();
                $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;

                $newPosterPath = 'posters/' . $uniqueFileName;

                // Delete old poster if it exists
                if ($posterPath && Storage::exists($posterPath)) {
                    Storage::delete($posterPath);
                }

                // Store the new poster
                Storage::put($newPosterPath, file_get_contents($file));
                $posterPath = $newPosterPath; // Update the poster path
            }

            // Update the event
            $event->update([
                'event_title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'platform' => $request->platform,
                'objective' => $request->objective,
                'poster_path' => $posterPath, // Save the updated poster path
                'period' => $periodCount + 1,
                'status' => 'Draft',
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Event updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Event update failed: ' . $th->getMessage());
        }
    }

    public function schedule($id)
    {
        $data = Event::findOrFail($id);
        $existingSchedules = Event_schedule::join('events', 'events.id', '=', 'event_schedules.event_guid')
            ->where('event_schedules.event_guid', $id)
            ->get();
        $startDate = \Carbon\Carbon::parse($data->start_date); // Use Carbon to work with dates
        $endDate = \Carbon\Carbon::parse($data->end_date);
        return view('admin.event.schedule', compact('data', 'existingSchedules', 'startDate', 'endDate'));
    }

    public function schedule_update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'schedules.*.day_name' => 'required|string',
            'schedules.*.target' => 'required|integer',
            'schedules.*.business_zone' => 'required|string',
            'schedules.*.event_vanue' => 'required|string',
            'schedules.*.date' => 'required|date',
            'schedules.*.activities.*.time' => 'required|date_format:H:i',
            'schedules.*.activities.*.description' => 'required|string|max:255',
        ]);


        // Fetch the event
        $event = Event::findOrFail($id);

        // Check if the "Clear Schedule" button was clicked
        if ($request->has('clear_schedule')) {
            // Fetch the event by ID and delete all schedules related to this event
            $event = Event::findOrFail($id);
            $event->schedules()->delete();

            return redirect()->back()->with('success', 'All schedules have been cleared.');
        }

        // Iterate over the schedules and save them
        foreach ($validated['schedules'] as $scheduleData) {
            // Check if the schedule already exists for the given day_name and date
            $existingSchedule = $event->schedules()->where('day_name', $scheduleData['day_name'])
                ->where('event_date', $scheduleData['date'])
                ->first();

            if (!$existingSchedule) {
                $event->schedules()->create([
                    'day_name' => $scheduleData['day_name'],
                    'event_date' => $scheduleData['date'],
                    'target' => $scheduleData['target'],
                    'business_zone' => $scheduleData['business_zone'],
                    'event_vanue' => $scheduleData['event_vanue'],
                    'activity' => json_encode($scheduleData['activities'])
                ]);
            }
        }

        return redirect()->back()->with('success', 'Schedules saved successfully!');
    }

    public function reward($id)
    {
        $data = Event::findOrFail($id);
        $reward = Event_reward::where('event_guid', $id)->first();
        if (!$reward) {
            // Handle the case where no reward is found.
            // You can either pass an empty object or create a new reward entry if you want.
            $reward = new Event_reward();
            $reward->event_guid = $id;  // Ensure the new reward has the correct event_guid
            $reward->prize = json_encode([
                'internal' => ['first' => '', 'second' => '', 'third' => ''],
                'external' => ['first' => '', 'second' => '', 'third' => ''],
            ]);
            $reward->condition = json_encode([
                'internal' => '',
                'external' => '',
            ]);
        }
        return view('admin.event.reward', compact('data', 'reward'));
    }

    public function reward_update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'prizes.internal.first' => 'required|numeric',
            'prizes.internal.second' => 'required|numeric',
            'prizes.internal.third' => 'required|numeric',
            'conditions.internal' => 'required|string',
            'prizes.external.first' => 'required|numeric',
            'prizes.external.second' => 'required|numeric',
            'prizes.external.third' => 'required|numeric',
            'conditions.external' => 'required|string',
        ]);

        // Prepare the prize and condition data
        $data = [
            'event_guid' => $id, // Make sure you're using the event id
            'prize' => json_encode([
                'internal' => [
                    'first' => $request->input('prizes.internal.first'),
                    'second' => $request->input('prizes.internal.second'),
                    'third' => $request->input('prizes.internal.third'),
                ],
                'external' => [
                    'first' => $request->input('prizes.external.first'),
                    'second' => $request->input('prizes.external.second'),
                    'third' => $request->input('prizes.external.third'),
                ]
            ]),
            'condition' => json_encode([
                'internal' => $request->input('conditions.internal'),
                'external' => $request->input('conditions.external')
            ]),
        ];

        // Check if the event reward already exists for this event
        $eventReward = Event_reward::where('event_guid', $id)->first();

        if ($eventReward) {
            // If the EventReward exists, update it
            $eventReward->update($data);
            $message = 'Event rewards updated successfully.';
        } else {
            // If the EventReward does not exist, create a new one
            Event_reward::create($data);
            $message = 'Event rewards created successfully.';
        }

        // Redirect back with the success message
        return redirect()->route('event.progress.reward', $id)
            ->with('success', $message);
    }


    public function checkProgress_main($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Define the completion criteria (adjust as per your database schema)
        $isComplete = $event->event_title && $event->start_date && $event->end_date && $event->objective && $event->platform;

        return response()->json(['complete' => $isComplete]);
    }

    public function checkProgress_schedule($eventId)
    {
        // Attempt to find the event schedule by event_guid
        $event = Event_schedule::where('event_guid', $eventId)->first();

        if ($event == null) {
            return response()->json(['complete' =>  null]);
        }

        // Define the completion criteria
        $isComplete = $event->day_name && $event->event_date && $event->activity;

        return response()->json(['complete' =>  $isComplete]);
    }

    public function checkProgress_reward($eventId)
    {
        // Attempt to find the event schedule by event_guid
        $event = Event_reward::where('event_guid', $eventId)->first();

        if ($event == null) {
            return response()->json(['complete' =>  null]);
        }

        // Define the completion criteria
        $isComplete = $event->prize && $event->condition;

        return response()->json(['complete' =>  $isComplete]);
    }
}
