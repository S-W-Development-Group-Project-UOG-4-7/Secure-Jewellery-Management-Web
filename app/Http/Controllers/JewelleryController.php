<?php

namespace App\Http\Controllers;

use App\Models\Jewellery;
use Illuminate\Http\Request;

class JewelleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Jewellery::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('type', 'ilike', "%{$search}%")
                  ->orWhere('metal', 'ilike', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        $jewellery = $query->latest()->paginate(10);

        return view('jewellery.index', compact('jewellery'));
    }

    public function create()
    {
        return view('jewellery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'metal' => 'required|string|max:50',
            'weight' => 'required|numeric|min:0.01',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:available,in_locker,sold',
        ]);

        Jewellery::create($validated);

        return redirect()->route('jewellery.index')->with('success', 'Jewellery item created successfully');
    }

    public function edit(Jewellery $jewellery)
    {
        return view('jewellery.edit', compact('jewellery'));
    }

    public function update(Request $request, Jewellery $jewellery)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'metal' => 'required|string|max:50',
            'weight' => 'required|numeric|min:0.01',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:available,in_locker,sold',
        ]);

        $jewellery->update($validated);

        return redirect()->route('jewellery.index')->with('success', 'Jewellery item updated successfully');
    }

    public function destroy(Jewellery $jewellery)
    {
        $jewellery->delete();

        return redirect()->route('jewellery.index')->with('success', 'Jewellery item deleted successfully');
    }
}
