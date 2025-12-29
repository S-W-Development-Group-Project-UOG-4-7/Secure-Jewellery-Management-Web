<?php

namespace App\Http\Controllers;

use App\Models\Jewellery;
use Illuminate\Http\Request;

class JewelleryController extends Controller
{
    /**
     * Display a listing of the jewellery.
     */
    public function index(Request $request)
    {
        $query = Jewellery::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('name', 'ilike', "%{$q}%"); // PostgreSQL search
        }

        $jewellery = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('jewellery.index', compact('jewellery'));
    }

    /**
     * Show the form for creating a new jewellery item.
     */
    public function create()
    {
        return view('jewellery.create');
    }

    /**
     * Store a newly created jewellery item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'nullable|string|max:100',
            'metal'  => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'value'  => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
        ]);

        Jewellery::create($validated);

        return redirect()
            ->route('jewellery.index')
            ->with('success', 'Jewellery added successfully!');
    }

    /**
     * Show the form for editing the specified jewellery item.
     */
    public function edit(Jewellery $jewellery)
    {
        return view('jewellery.edit', compact('jewellery'));
    }

    /**
     * Update the specified jewellery item.
     */
    public function update(Request $request, Jewellery $jewellery)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'nullable|string|max:100',
            'metal'  => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'value'  => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
        ]);

        $jewellery->update($validated);

        return redirect()
            ->route('jewellery.index')
            ->with('success', 'Jewellery updated successfully!');
    }

    /**
     * Remove the specified jewellery item.
     */
    public function destroy(Jewellery $jewellery)
    {
        $jewellery->delete();

        return redirect()
            ->route('jewellery.index')
            ->with('success', 'Jewellery deleted successfully!');
    }
}
