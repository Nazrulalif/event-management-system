<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Agent_group;
use App\Models\Event_budget;
use App\Models\Event_reward;
use App\Models\Event_schedule;
use App\Models\Event_target;
use App\Models\Staff_group;

class HomeController extends Controller
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

        return view('agent.home.index', compact('upcomingEvents'));
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
    public function view($id)
    {
        $data = Event::findOrFail($id);
        $start_date = Carbon::parse($data->start_date)->format('d/m/Y');
        $end_date = Carbon::parse($data->start_date)->format('d/m/Y');
        $start_time = Carbon::parse($data->start_time)->format('H:i A');
        $end_time = Carbon::parse($data->end_time)->format('H:i A');

        $schedule = Event_schedule::where('event_guid', $id)->get();
        $staff = Staff_group::select(
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

        $agent = Agent_group::select(
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

        $target = Event_target::where('event_guid', $id)->get();
        $budget = Event_budget::join('budget_items', 'budget_items.budget_guid', '=', 'event_budgets.id')
            ->where('event_budgets.event_guid', $id)->get();

        $budget = Event_budget::join('budget_items', 'budget_items.budget_guid', '=', 'event_budgets.id')
            ->where('event_budgets.event_guid', $id)
            ->get();

        $total_budget = $budget->sum('total_budget');
        $fee_percent = 10; // Example percentage, adjust as needed
        $fee = ($fee_percent / 100) * $total_budget;

        $tax_percent = 6; // Service tax percentage
        $tax = ($tax_percent / 100) * ($total_budget + $fee);

        $grand_total = $total_budget + $fee + $tax;

        $reward = Event_reward::where('event_guid', $id)->first(); // Get the reward for the specific event
        $prizes = json_decode($reward->prize, true); // Decode the JSON data
        $internalPrizes = $prizes['internal'];
        $externalPrizes = $prizes['external'];
        $condition = json_decode($reward->condition, true); // Decode the JSON data
        $internalCondition = $condition['internal'];
        $externalCondition = $condition['external'];

        return view('agent.home.view', compact(
            'data',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'schedule',
            'staff',
            'agent',
            'target',
            'budget',
            'total_budget',
            'fee_percent',
            'fee',
            'tax_percent',
            'tax',
            'grand_total',
            'internalPrizes',
            'internalCondition',
            'externalPrizes',
            'externalCondition',
        ));
    }

    public function print($id)
    {
        $data = Event::findOrFail($id);
        $start_date = Carbon::parse($data->start_date)->format('d/m/Y');
        $end_date = Carbon::parse($data->start_date)->format('d/m/Y');
        $start_time = Carbon::parse($data->start_time)->format('H:i A');
        $end_time = Carbon::parse($data->end_time)->format('H:i A');

        $schedule = Event_schedule::where('event_guid', $id)->get();
        $staff = Staff_group::select(
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

        $agent = Agent_group::select(
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

        $target = Event_target::where('event_guid', $id)->get();
        $budget = Event_budget::join('budget_items', 'budget_items.budget_guid', '=', 'event_budgets.id')
            ->where('event_budgets.event_guid', $id)->get();

        $budget = Event_budget::join('budget_items', 'budget_items.budget_guid', '=', 'event_budgets.id')
            ->where('event_budgets.event_guid', $id)
            ->get();

        $total_budget = $budget->sum('total_budget');
        $fee_percent = 10; // Example percentage, adjust as needed
        $fee = ($fee_percent / 100) * $total_budget;

        $tax_percent = 6; // Service tax percentage
        $tax = ($tax_percent / 100) * ($total_budget + $fee);

        $grand_total = $total_budget + $fee + $tax;

        $reward = Event_reward::where('event_guid', $id)->first(); // Get the reward for the specific event
        $prizes = json_decode($reward->prize, true); // Decode the JSON data
        $internalPrizes = $prizes['internal'];
        $externalPrizes = $prizes['external'];
        $condition = json_decode($reward->condition, true); // Decode the JSON data
        $internalCondition = $condition['internal'];
        $externalCondition = $condition['external'];

        return view('agent.home.print', compact(
            'data',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'schedule',
            'staff',
            'agent',
            'target',
            'budget',
            'total_budget',
            'fee_percent',
            'fee',
            'tax_percent',
            'tax',
            'grand_total',
            'internalPrizes',
            'internalCondition',
            'externalPrizes',
            'externalCondition',
        ));
    }
}
