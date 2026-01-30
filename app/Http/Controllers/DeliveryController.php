<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Supplier;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with('supplier');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'ilike', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        if ($request->has('supplier_id') && $request->supplier_id !== '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        $deliveries = $query->latest('delivery_date')->paginate(10);
        $suppliers = Supplier::orderBy('name')->get();

        return view('deliveries.index', compact('deliveries', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('deliveries.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'invoice_image' => 'nullable|image|mimes:jpeg,jpg,png,pdf|max:5120',
            'delivery_date' => 'required|date',
        ]);

        if ($request->hasFile('invoice_image')) {
            $path = $request->file('invoice_image')->store('invoices', 'public');
            $validated['invoice_image'] = $path;
        }

        $delivery = Delivery::create($validated);

        Stock::updateOrCreateStock(
            $validated['item_name'],
            $validated['quantity'],
            Auth::id()
        );

        return redirect()->route('deliveries.index')->with('success', 'Delivery recorded and stock updated successfully');
    }

    public function edit(Delivery $delivery)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('deliveries.edit', compact('delivery', 'suppliers'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'invoice_image' => 'nullable|image|mimes:jpeg,jpg,png,pdf|max:5120',
            'delivery_date' => 'required|date',
        ]);

        if ($request->hasFile('invoice_image')) {
            if ($delivery->invoice_image) {
                Storage::disk('public')->delete($delivery->invoice_image);
            }
            $path = $request->file('invoice_image')->store('invoices', 'public');
            $validated['invoice_image'] = $path;
        }

        $oldQuantity = $delivery->quantity;
        $oldItemName = $delivery->item_name;

        $delivery->update($validated);

        if ($oldItemName === $validated['item_name']) {
            $quantityDiff = $validated['quantity'] - $oldQuantity;
            if ($quantityDiff != 0) {
                Stock::updateOrCreateStock(
                    $validated['item_name'],
                    $quantityDiff,
                    Auth::id()
                );
            }
        } else {
            Stock::updateOrCreateStock(
                $oldItemName,
                -$oldQuantity,
                Auth::id()
            );
            Stock::updateOrCreateStock(
                $validated['item_name'],
                $validated['quantity'],
                Auth::id()
            );
        }

        return redirect()->route('deliveries.index')->with('success', 'Delivery updated and stock adjusted successfully');
    }

    public function destroy(Delivery $delivery)
    {
        Stock::updateOrCreateStock(
            $delivery->item_name,
            -$delivery->quantity,
            Auth::id()
        );

        if ($delivery->invoice_image) {
            Storage::disk('public')->delete($delivery->invoice_image);
        }

        $delivery->delete();

        return redirect()->route('deliveries.index')->with('success', 'Delivery deleted and stock adjusted successfully');
    }
}
