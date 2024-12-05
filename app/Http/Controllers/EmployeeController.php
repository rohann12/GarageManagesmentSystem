<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        $employees = User::all();
        return view('Employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('Employees.create');
    }
    public function Profile()
    {
        $user = User::Find(auth()->id());

        //Count unique completed orders for the authenticated user
        $completedOrdersCount = $user->countUniqueCompletedOrders();

        // Count total repairs done
        $repairCount = $user->repairs()->count();

        // Daily XP calculation (implement this method according to your logic)
        $dailyXP = $this->calculateDailyXP($user);

        // Level calculation logic (implement this method according to your logic)
        $user->level = $this->calculateUserLevel($user->experience_points);

        return view('Employees.profile', compact('user', 'repairCount', 'completedOrdersCount', 'dailyXP'));
    }

    private function calculateDailyXP($user)
    {
        // Get today's date
        $today = now()->startOfDay();

        // Sum XP from repairs done today
        $dailyXP = $user->repairs()
            ->whereDate('created_at', '=', $today)
            ->sum('Xp'); // Sum the 'Xp' field

        return $dailyXP;
    }

    private function calculateUserLevel($experiencePoints)
    {
        // Example logic for assigning more levels
        if ($experiencePoints < 100) {
            return 'Beginner';
        } elseif ($experiencePoints < 300) {
            return 'Novice';
        } elseif ($experiencePoints < 600) {
            return 'Intermediate';
        } elseif ($experiencePoints < 1000) {
            return 'Advanced';
        } elseif ($experiencePoints < 1500) {
            return 'Expert';
        } elseif ($experiencePoints < 2000) {
            return 'Master';
        } else {
            return 'Legend';
        }
    }


    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Convert 'on' to 1 and null to 0
        $isAdmin = $request->has('isAdmin') ? 1 : 0;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'isAdmin' => $isAdmin,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }


    /**
     * Show the form for editing the specified employee.
     */
    public function edit(User $employee)
    {
        return view('Employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only(['name', 'email', 'isAdmin']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
