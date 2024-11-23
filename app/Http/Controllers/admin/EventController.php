<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\EventApprovedEmail;
use App\Mail\EventRescheduleEmail;
use App\Models\Agent;
use App\Models\Agent_group;
use App\Models\Agent_member;
use App\Models\AgentMember_Group;
use App\Models\Budget_item;
use App\Models\Event;
use App\Models\Event_budget;
use App\Models\Event_reward;
use App\Models\Event_schedule;
use App\Models\Event_target;
use App\Models\Staff_group;
use App\Models\StaffMember_group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

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

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return response()->json([
            'event_title' => $event->event_title,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'start_time' => $event->start_time,
            'end_time' => $event->end_time,
        ]);
    }

    public function update(Request $request)
    {

        $event = Event::findOrFail($request->id);

        try {
            $startDate = Carbon::parse($request->start_date); // Parse the start date
            $endDate = Carbon::parse($request->end_date);
            // Calculate the difference in days
            $periodCount = $startDate->diffInDays($endDate);

            $event->update([
                'event_title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'period' =>  $periodCount + 1
            ]);

            // Get all users to send the email to
            $users = User::where('is_approve', 'Y')->where('is_active', 'Y')->get(); // Modify this if you want to filter users

            // Loop through all users and send the event approval email
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new EventRescheduleEmail($event));
            }

            return redirect()->back()->with('success', 'Event updated successfully.');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Event updated Failed.');
        }
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
            // Update event status to "Approve"
            $event->status = 'Approve';
            $event->save();

            // Get all users to send the email to
            $users = User::where('is_approve', 'Y')->where('is_active', 'Y')->get(); // Modify this if you want to filter users

            // Loop through all users and send the event approval email
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new EventApprovedEmail($event));
            }

            return response()->json(['message' => 'Event request approved successfully, notifications sent to all users.']);
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

            return redirect()->route('event.progress.schedule', $id)->with('success', 'Event updated successfully.');
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

        return redirect()->route('event.progress.staff', $id)->with('success', 'Schedules saved successfully!');
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

        $isReward = Event_reward::where('event_guid', $id)->count();
        $isSchedule = Event_schedule::where('event_guid', $id)->count();
        $isStaff = Staff_group::where('event_guid', $id)->count();
        $isAgent = Agent_group::where('event_guid', $id)->count();
        $isTarget = Event_target::where('event_guid', $id)->count();
        $isBudget = Event_budget::where('event_guid', $id)->count();
        $isComplete = false;
        if ($isReward >= 1 && $isAgent  >=  1 &&  $isBudget  >=  1 && $isSchedule  >=  1 && $isStaff  >=  1 && $isTarget  >=  1) {
            $isComplete = true;
        }


        return view('admin.event.reward', compact(
            'data',
            'reward',
            'isComplete'
        ));
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

        if ($request->isComplete) {
            $event = Event::findOrFail($id);
            $event->update([
                'status' => 'Approve'
            ]);

            // Get all users to send the email to
            $users = User::where('is_approve', 'Y')->where('is_active', 'Y')->get(); // Modify this if you want to filter users

            // Loop through all users and send the event approval email
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new EventApprovedEmail($event));
            }

            return redirect()->route('calendar.index')->with('success', 'The event has been successfully added to the calendar.');
        }

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

    public function target($id)
    {
        $data = Event::findOrFail($id);

        $products = [
            'UNIFI FIXED ONLY',
            'UNIFI FIXED WITH MOBILE (FMC)',
            'UNIFI FIXED WITH MOBILE AND DEVICE (FMC)',
            'UNIFI FIXED WITH MOBILE AND ENTERTAINMENT (FMCC)',
            'UNIFI FIXED WITH ENTERTAINMENT (FCC)',
            'WINBACK HSBA',
            'WINBACK TIME',
            'UNIFI FIXED WITH LIFESTYLE (FLC)',
            'UNIBIG POSTPAID 99',
            'UNIBIG POSTPAID 69',
            'UNIBIG POSTPAID 99',
            'UNIBIG POSTPAID FAMILY 129',
            'UNIBIG POSTPAID FAMILY 159',
            'UNIBIG POSTPAID FAMILY 189',
            'UNIBIG WOW 10',
            'UNIBIG WOW 25',
            'UNIBIG WOW 35',
            'UNIBIG POSTPAID 99',
            'UNIBIG POSTPAID 69',
            'UNIBIG POSTPAID 99',
            'UNIBIG POSTPAID FAMILY 129',
            'UNIBIG POSTPAID FAMILY 159',
            'UNIBIG POSTPAID FAMILY 189',
            'UNIBIG WOW 10',
            'UNIBIG WOW 25',
            'UNIBIG WOW 35'
        ];

        $targets = Event_target::where('event_guid', $id)->get();

        return view('admin.event.target', compact('data', 'products', 'targets'));
    }

    public function target_update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'products.*.product' => 'required|string',
            'products.*.arpu' => 'required|numeric|min:0',
            'products.*.sales_physical_target' => 'required|integer|min:0',
            'products.*.outbase' => 'required|integer|min:0',
            'products.*.inbase' => 'required|integer|min:0',
            'products.*.revenue' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Delete removed records if any
            if ($request->has('deleted_ids')) {
                Event_target::whereIn('id', $request->deleted_ids)->delete();
            }

            // Update existing targets and add new ones
            foreach ($request->products as $key => $product) {
                if (!empty($product['id'])) {
                    // Update existing record
                    Event_target::where('id', $product['id'])
                        ->update([
                            'product' => $product['product'],
                            'arpu' => $product['arpu'],
                            'sales_physical_target' => $product['sales_physical_target'],
                            'outbase' => $product['outbase'],
                            'inbase' => $product['inbase'],
                            'revenue' => $product['revenue'],
                        ]);
                } else {
                    // Create a new record
                    Event_target::create([
                        'product' => $product['product'],
                        'arpu' => $product['arpu'],
                        'sales_physical_target' => $product['sales_physical_target'],
                        'outbase' => $product['outbase'],
                        'inbase' => $product['inbase'],
                        'revenue' => $product['revenue'],
                        'event_guid' => $id
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('event.progress.budget', $id)->with('success', 'Products updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update products: ' . $e->getMessage())->withInput();
        }
    }

    public function target_delete($id)
    {
        $target = Event_target::find($id);
        if ($target) {
            $target->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
    }

    public function budget($id)
    {
        $data = Event::findOrFail($id); // Fetch the event
        $budget = Event_budget::where('event_guid', $id)
            ->with('budget_items')
            ->select('*')
            ->first();

        return view('admin.event.budget', compact('data', 'budget'));
    }

    public function budget_update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Check if budget exists for this event
            $budget = Event_budget::where('event_guid', $id)->first();

            if (!$budget) {
                // Create new budget
                $budget = Event_budget::create([
                    'event_guid' => $id,
                    'total' => $request->total,
                    'fee_percent' => $request->fee_percent,
                    'fee' => $request->fee,
                    'tax_percent' => $request->tax_percent,
                    'tax' => $request->tax,
                    'grand_total' => $request->grand_total,
                ]);
            } else {
                // Update existing budget
                $budget->update([
                    'total' => $request->total,
                    'fee_percent' => $request->fee_percent,
                    'fee' => $request->fee,
                    'tax_percent' => $request->tax_percent,
                    'tax' => $request->tax,
                    'grand_total' => $request->grand_total,
                ]);

                // Delete existing budget items
                Budget_item::where('budget_guid', $budget->id)->delete();
            }

            // Create budget items
            if (isset($request->items) && is_array($request->items)) {
                foreach ($request->items as $item) {
                    Budget_item::create([
                        'budget_guid' => $budget->id,
                        'description' => $item['description'],
                        'days' => $item['days'],
                        'frequency' => $item['frequency'],
                        'price_per_unit' => $item['price_per_unit'],
                        'total_budget' => $item['total_budget'],
                        'remark' => $item['remark'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('event.progress.reward', $id)->with('success', 'Budget data has been saved successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to save budget data. ' . $e->getMessage())->withInput();
        }
    }

    public function staff($id, Request $request)
    {

        if ($request->ajax()) {
            // Fetch groups with mentors, leaders, and members
            $data = Staff_group::select(
                'staff_groups.id as id',
                'staff_groups.group_name',
                'staff_groups.vehicle',
                'mentors.name as mentor_name',
                'leaders.name as leader_name',
                'staff_groups.created_at'
            )
                ->join('users as mentors', 'staff_groups.mentor', '=', 'mentors.id')
                ->join('users as leaders', 'staff_groups.leader', '=', 'leaders.id')
                ->where('staff_groups.event_guid', $id)
                ->with(['members' => function ($query) {
                    $query->select('users.id', 'users.name');
                }])
                ->with(['agent_members' => function ($query) {
                    $query->select('agents.id', 'agents.name');
                }])
                ->get();

            // Format the data for DataTables
            return DataTables::of($data)
                ->addColumn('members', function ($data) {
                    // Extract member names and join them with commas
                    $members = $data->members->pluck('name')->toArray();
                    $agent = $data->agent_members->pluck('name')->toArray();
                    $allMembers = array_merge($members, $agent);
                    return implode(', ', $allMembers);
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('d/m/Y');
                })
                ->rawColumns(['actions']) // Allow raw HTML for actions column
                ->make(true);
        }
        $data = Event::findOrFail($id); // Fetch the event
        $mentor = User::all();
        $leader = User::where('team', 'Sales Operation MKU (SO MKU) ')->orwhere('team', 'Sales Operation MKS (SO MKS)')->get();
        $member = User::where('team', '!=', 'Sales Operation MKU (SO MKU) ')->where('team', '!=', 'Sales Operation MKS (SO MKS)')->get();
        $nextar = Agent::where('channel', 'Nextstar')->get();

        return view('admin.event.staff', compact(
            'data',
            'mentor',
            'leader',
            'member',
            'nextar',
        ));
    }

    public function staff_update(Request $request, $id)
    {

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'mentor' => 'required|exists:users,id', // Ensure the mentor exists in the users table
            'vehicle' => 'required|string|max:255',
            'leader' => 'required|exists:users,id', // Ensure the leader exists in the users table
            'member' => 'required|array', // Member is an array
            'member.*' => 'exists:users,id', // Each member ID should exist in the users table
            'nextar' => 'nullable|array', // Member is an array
            'nextar.*' => 'exists:agents,id', // Each member ID should exist in the users table
        ]);

        // Create the group
        $group = Staff_group::create([
            'event_guid' => $id,
            'group_name' => $request->name,
            'mentor' => $request->mentor, // Get the mentor's name
            'vehicle' => $request->vehicle,
            'leader' => $request->leader,
        ]);

        // Add the selected members to the group
        foreach ($request->member as $memberId) {
            StaffMember_group::create([
                'group_guid' => $group->id,
                'staff_guid' => $memberId,
            ]);
        }

        if ($request->nextar) {
            // Add the selected members to the group
            foreach ($request->nextar as $agentId) {
                AgentMember_Group::create([
                    'group_guid' => $group->id,
                    'agent_guid' => $agentId,
                ]);
            }
        }

        // Redirect back with a success message
        return redirect()->route('event.progress.staff', $id)
            ->with('success', 'Group and members added successfully!');
    }

    public function staff_delete($id)
    {
        $event = Staff_group::find($id);
        if ($event) {
            $event->delete(); // Delete the user request
            return response()->json(['message' => 'Group deleted successfully']);
        }
        return response()->json(['message' => 'Group not found'], 404);
    }

    public function staff_show($id)
    {
        $group = Staff_group::findOrFail($id);

        return response()->json([
            'group_name' => $group->group_name,
            'mentor' => $group->mentor,
            'leader' => $group->leader,
            'vehicle' => $group->vehicle,
            'members' => $group->members->pluck('id'), // Return member IDs
            'nextar' => $group->agent_members->pluck('id')
        ]);
    }

    public function agent($id, Request $request)
    {

        if ($request->ajax()) {
            // Fetch groups with mentors, leaders, and members
            $data = Agent_group::select(
                'agent_groups.id as id',
                'users.name as nazir',
                'agent_groups.created_at'
            )
                ->join('users', 'users.id', '=', 'agent_groups.nazir')
                ->where('agent_groups.event_guid', $id)
                ->with(['members' => function ($query) {
                    $query->select('agents.id', 'agents.name');
                }])
                ->get();

            // Format the data for DataTables
            return DataTables::of($data)
                ->addColumn('members', function ($data) {
                    // Extract member names and join them with commas
                    $members = $data->members->pluck('name')->toArray();
                    return implode(', ', $members);
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('d/m/Y');
                })
                ->rawColumns(['actions']) // Allow raw HTML for actions column
                ->make(true);
        }
        $data = Event::findOrFail($id); // Fetch the event
        $nazir = User::where('team', 'Sales Operation MKU (SO MKU) ')->orwhere('team', 'Sales Operation MKS (SO MKS)')->get();
        $member = Agent::where('channel', '=', 'Rovers')->orWhere('channel', '=', 'Agents (UCA/UCP)')->get();

        return view('admin.event.agent', compact(
            'data',
            'nazir',
            'member',
        ));
    }

    public function agent_delete($id)
    {
        $event = Agent_group::find($id);
        if ($event) {
            $event->delete(); // Delete the user request
            return response()->json(['message' => 'Group deleted successfully']);
        }
        return response()->json(['message' => 'Group not found'], 404);
    }

    public function agent_show($id)
    {
        $group = Agent_group::findOrFail($id);

        return response()->json([
            'nazir' => $group->nazir,
            'members' => $group->members->pluck('id'), // Return member IDs
        ]);
    }

    public function staff_agent_update(Request $request)
    {
        $request->validate([
            'nazir' => 'required|exists:users,id',
            'member' => 'required|array',
            'member.*' => 'exists:agents,id',
        ]);

        // Update the group
        $group = Agent_group::findOrFail($request->id);
        $group->update([
            'nazir' => $request->nazir,
        ]);

        DB::table('agent_members')
            ->where('group_guid', $group->id)
            ->delete();
        // Insert new members
        foreach ($request->member as $memberId) {
            DB::table('agent_members')->insert([
                'id' => Str::uuid(), // Generate a new UUID
                'group_guid' => $group->id,
                'agent_guid' => $memberId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Group and members updated successfully!');
    }

    public function agent_update(Request $request, $id)
    {


        // Validate the request
        $request->validate([
            'nazir' => 'required|exists:users,id', // Ensure the mentor exists in the users table
            'member' => 'required|array', // Member is an array
            'member.*' => 'exists:agents,id', // Each member ID should exist in the users table

        ]);

        // Create the group
        $group = Agent_group::create([
            'event_guid' => $id,
            'nazir' => $request->nazir, // Get the mentor's name
        ]);

        // Add the selected members to the group
        foreach ($request->member as $memberId) {
            Agent_member::create([
                'group_guid' => $group->id,
                'agent_guid' => $memberId,
            ]);
        }

        // Redirect back with a success message
        return redirect()->route('event.progress.agent', $id)
            ->with('success', 'Group and members added successfully!');
    }

    public function staff_edit_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mentor' => 'required|exists:users,id',
            'leader' => 'required|exists:users,id',
            'vehicle' => 'required|string',
            'member' => 'required|array',
            'member.*' => 'exists:users,id',
        ]);

        // Update the group
        $group = Staff_group::findOrFail($request->id);
        $group->update([
            'group_name' => $request->name,
            'mentor' => $request->mentor,
            'leader' => $request->leader,
            'vehicle' => $request->vehicle,
        ]);

        DB::table('staff_member_groups')
            ->where('group_guid', $group->id)
            ->delete();

        DB::table('agent_member_groups')
            ->where('group_guid', $group->id)
            ->delete();


        // Insert new members
        foreach ($request->member as $memberId) {
            DB::table('staff_member_groups')->insert([
                'id' => Str::uuid(), // Generate a new UUID
                'group_guid' => $group->id,
                'staff_guid' => $memberId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        foreach ($request->nextar as $agentId) {
            DB::table('agent_member_groups')->insert([
                'id' => Str::uuid(), // Generate a new UUID
                'group_guid' => $group->id,
                'agent_guid' => $agentId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Group and members updated successfully!');
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

    public function checkProgress_target($eventId)
    {
        // Attempt to find the event schedule by event_guid
        $event = Event_target::where('event_guid', $eventId)->first();

        if ($event == null) {
            return response()->json(['complete' =>  null]);
        }

        // Define the completion criteria
        $isComplete = $event->product && $event->revenue;

        return response()->json(['complete' =>  $isComplete]);
    }

    public function checkProgress_budget($eventId)
    {
        // Attempt to find the event schedule by event_guid
        $event = Event_budget::where('event_guid', $eventId)->first();

        if ($event == null) {
            return response()->json(['complete' =>  null]);
        }

        // Define the completion criteria
        $isComplete = $event->total && $event->grand_total;

        return response()->json(['complete' =>  $isComplete]);
    }

    public function checkProgress_staff($eventId)
    {
        // Attempt to find the event schedule by event_guid
        $event = Staff_group::where('event_guid', $eventId)->first();

        if ($event == null) {
            return response()->json(['complete' =>  null]);
        }

        // Define the completion criteria
        $isComplete = $event->group_name && $event->mentor;

        return response()->json(['complete' =>  $isComplete]);
    }

    public function checkProgress_agent($eventId)
    {
        // Attempt to find the event schedule by event_guid
        $event = Agent_group::where('event_guid', $eventId)->first();

        if ($event == null) {
            return response()->json(['complete' =>  null]);
        }

        // Define the completion criteria
        $isComplete = $event->nazir;

        return response()->json(['complete' =>  $isComplete]);
    }
}
