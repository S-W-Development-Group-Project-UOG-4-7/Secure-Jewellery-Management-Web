<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    public function index(Request $request)
    {
        $query = Locker::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('locker_number', 'ilike', "%{$search}%")
                  ->orWhere('location', 'ilike', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $lockers = $query->latest()->paginate(10);

        return view('lockers.index', compact('lockers'));
    }

    public function create()
    {
        return view('lockers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'locker_number' => 'required|string|max:50|unique:lockers,locker_number',
            'location' => 'required|string|max:100',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        Locker::create($validated);

        return redirect()->route('lockers.index')->with('success', 'Locker created successfully');
    }

    public function edit(Locker $locker)
    {
        return view('lockers.edit', compact('locker'));
    }

    public function update(Request $request, Locker $locker)
    {
        $validated = $request->validate([
            'locker_number' => 'required|string|max:50|unique:lockers,locker_number,' . $locker->id,
            'location' => 'required|string|max:100',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $locker->update($validated);

        return redirect()->route('lockers.index')->with('success', 'Locker updated successfully');
    }

    public function destroy(Locker $locker)
    {
        $locker->delete();

        return redirect()->route('lockers.index')->with('success', 'Locker deleted successfully');
    }
}
