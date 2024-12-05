<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::all()->sortBy('quantity');
        return view('parts.index', compact('parts'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:available,discontinued',
        ]);
        Part::create($request->all());
        return redirect()->route('parts.index')->with('success', 'Part created successfully');
    }
    public function update(Request $request, Part $part)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:available,discontinued',
        ]);
        $part->update($request->all());
        return redirect()->route('parts.index')->with('success', 'Part updated successfully');
    }
    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part deleted successfully');
    }

}
