<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CalendarController extends Controller
{
    public function index()
    {
        // Get upcoming events (start date is in the future and status is 'Approve')
        $upcomingEvents = Event::select('events.*', 'users.name as creator_name', 'events.id as id')  // Select event data along with the user's name
            ->join('users', 'users.id', '=', 'events.created_by')
            ->where('events.status', 'Approve')
            ->where('events.start_date', '>=', now()->toDateString())  // Filter by today or future events
            ->orderBy('events.start_date', 'asc') // Order by start date, ascending
            ->limit(5)
            ->get();

        return view('admin.calendar.index', compact('upcomingEvents'));
    }

    public function getEvents()
    {
        // Fetch events from the database with user data
        $events = Event::select('events.*', 'users.name as creator_name', 'events.id as id')  // Select event data along with the user's name
            ->join('users', 'users.id', '=', 'events.created_by')
            ->where('events.status', 'Approve') // Only get events with approved users
            ->get();

        // Format events for FullCalendar
        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id, // Event title
                'title' => $event->event_title, // Event title
                'start' => $event->start_date . 'T' . $event->start_time, // Full start datetime (YYYY-MM-DDTHH:MM:SS)
                'end' => $event->end_date . 'T' . $event->end_time, // Full end datetime (YYYY-MM-DDTHH:MM:SS)
                'description' => $event->objective, // Description (objective of the event)
                'start_time' => Carbon::parse($event->start_time)->format('g:i A'), // Format start time to 12-hour format
                'end_time' => Carbon::parse($event->end_time)->format('g:i A'),
                'start_date' => Carbon::parse($event->start_date)->format('l, d/m/Y'), // Format start date
                'end_date' => Carbon::parse($event->end_date)->format('l, d/m/Y'),
                'platform' => $event->platform, // Platform (e.g., Landed, Highrise)
                'creator_name' => $event->creator_name, // Creator's name (from users table)
            ];
        });

        // Return the formatted events as a JSON response
        return response()->json($formattedEvents);
    }

    public function destroy($eventId)
    {
        try {
            // Find and delete the event by ID
            $event = Event::findOrFail($eventId);
            $event->delete();

            // Return a success response
            return response()->json(['message' => 'Event deleted successfully.'], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['message' => 'Error deleting event.'], 500);
        }
    }


    public function store(Request $request)
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
        ]);

        try {
            $startDate = Carbon::parse($request->start_date); // Parse the start date
            $endDate = Carbon::parse($request->end_date);
            // Calculate the difference in days
            $periodCount = $startDate->diffInDays($endDate);
            // Handle file upload if there is a poster
            $posterPath = null;
            if ($request->hasFile('poster')) {
                $file = $request->file('poster');
                $extension = $file->getClientOriginalExtension();
                $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;

                // Use Storage::put to store the file in the 'posters' folder
                $posterPath = 'posters/' . $uniqueFileName;

                // Put the file in the 'public' disk (storage/app/public)
                Storage::put($posterPath, file_get_contents($file));
            }

            // Create the event
            $event = Event::create([
                'event_title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'platform' => $request->platform,
                'objective' => $request->objective,
                'poster_path' => $posterPath ? $posterPath : null,
                'period' => $periodCount + 1,
                'status' => 'Draft',
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('event.progress', $event->id)->with('success', 'Event added successfully.');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Event add failed: ' . $th->getMessage());
        }
    }
}
