<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomOrderController extends Controller
{
    // --- 1.1 Custom Order UI Logic (Show Form) ---
    public function create() {
        return view('customer.custom_order_create');
    }

    // --- 1.1 Store New Order ---
    public function store(Request $request) {
        $request->validate([
            'jewellery_type' => 'required|string|max:255',
            'metal_type' => 'required|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'design_image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $path = null;
        if ($request->hasFile('design_image')) {
            $path = $request->file('design_image')->store('designs', 'public');
        }

        CustomOrder::create([
            'user_id' => Auth::id(),
            'jewellery_type' => $request->jewellery_type,
            'metal_type' => $request->metal_type,
            'budget' => $request->budget,
            'description' => $request->description,
            'design_image_path' => $path,
            'status' => 'pending'
        ]);

        return redirect()->route('custom.index')->with('success', 'Order submitted successfully!');
    }

    // --- 1.3 Order Tracking List (My Orders) ---
    public function index() {
        $orders = CustomOrder::where('user_id', Auth::id())
                             ->orderBy('created_at', 'desc')
                             ->get();
        return view('customer.order_tracking', compact('orders'));
    }

    // --- 1.3 Order Tracking Detail (Timeline) ---
    public function show($id) {
        $order = CustomOrder::where('user_id', Auth::id())->findOrFail($id);
        return view('customer.order_timeline', compact('order'));
    }

    // --- 1.2 Manager Approval List ---
    public function managerIndex() {
        if (Auth::user()->role !== 'Admin' && Auth::user()->role !== 'Manager') { 
            abort(403); 
        }
        
        $pendingOrders = CustomOrder::where('status', 'pending')->with('user')->get();
        return view('manager.approvals', compact('pendingOrders'));
    }

    // --- 1.2 Manager Action (Approve/Reject) ---
    public function approve(Request $request, $id) {
        if (Auth::user()->role !== 'Admin' && Auth::user()->role !== 'Manager') { 
            abort(403); 
        }

        $order = CustomOrder::findOrFail($id);
        
        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'manager_comments' => 'nullable|string|max:1000'
        ]);

        $order->status = $request->decision;
        $order->manager_comments = $request->manager_comments;
        
        if($request->decision === 'approved') {
            $order->approved_at = now();
        }
        
        $order->save();

        return back()->with('success', "Order #{$id} has been " . ucfirst($request->decision) . ".");
    }
}