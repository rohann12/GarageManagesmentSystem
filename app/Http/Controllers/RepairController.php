<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::all();
        return view('repairs.index', compact('repairs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_name' => 'required|string|max:255',
            'job_complexity' => 'required',
            'estimated_duration' => 'required',
        ]);

        Repair::create($request->all());

        return redirect()->route('repairs.index')->with('success', 'Repair created successfully.');
    }

    public function update(Request $request, Repair $repair)
    {
        $request->validate([
            'job_name' => 'required|string|max:255',
            'job_complexity' => 'required',
            'estimated_duration' => 'required',
        ]);

        $repair->update($request->all());

        return redirect()->route('repairs.index')->with('success', 'Repair updated successfully.');
    }

    public function destroy(Repair $repair)
    {
        $repair->delete();

        return redirect()->route('repairs.index')->with('success', 'Repair deleted successfully.');
    }
}
