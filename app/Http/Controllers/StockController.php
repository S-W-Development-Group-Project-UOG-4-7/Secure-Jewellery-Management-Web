<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with('updatedByUser');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('item_name', 'ilike', "%{$search}%");
        }

        if ($request->has('low_stock') && $request->low_stock == '1') {
            $threshold = $request->threshold ?? 10;
            $query->where('quantity', '<=', $threshold);
        }

        $stocks = $query->latest('last_updated')->paginate(15);

        $totalItems = Stock::count();
        $totalQuantity = Stock::sum('quantity');
        $lowStockCount = Stock::where('quantity', '<=', 10)->count();

        return view('stock.index', compact('stocks', 'totalItems', 'totalQuantity', 'lowStockCount'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->quantity = $validated['quantity'];
        $stock->last_updated = now();
        $stock->updated_by = Auth::id();
        $stock->save();

        return redirect()->route('stock.index')->with('success', 'Stock quantity updated successfully');
    }
}
