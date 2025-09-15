<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScheduleController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $schedules = Schedule::with('user')->latest()->paginate(10);
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('schedules.create', compact('days'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $user = \Illuminate\Support\Facades\Auth::user();

        /** @var \App\Models\User $user */ // This line is the fix!

        $schedule = $user->schedules()->create($request->only('name'));

        foreach ($request->days as $day => $data) {
            if (!empty($data['body_part'])) {
                $schedule->scheduleDays()->create([
                    'day_of_week' => $day,
                    'body_part' => $data['body_part']
                ]);
            }
        }
        return redirect()->route('schedules.index')->with('success', 'Schedule created!');
    }

    public function show(Schedule $schedule)
    {
        // Load the schedule's daily plans and format them
        $scheduleData = $schedule->load('scheduleDays');
        return response()->json($scheduleData);
    }

    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule); // Use the policy
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $scheduleDays = $schedule->scheduleDays->keyBy('day_of_week');
        return view('schedules.edit', compact('schedule', 'days', 'scheduleDays'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule); // Use the policy
        $request->validate(['name' => 'required|string|max:255']);

        $schedule->update($request->only('name'));
        $schedule->scheduleDays()->delete(); // Easiest way: delete old days

        foreach ($request->days as $day => $data) {
            if (!empty($data['body_part'])) {
                $schedule->scheduleDays()->create([
                    'day_of_week' => $day,
                    'body_part' => $data['body_part']
                ]);
            }
        }
        return redirect()->route('schedules.index')->with('success', 'Schedule updated!');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule); // Use the policy
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted!');
    }
}
