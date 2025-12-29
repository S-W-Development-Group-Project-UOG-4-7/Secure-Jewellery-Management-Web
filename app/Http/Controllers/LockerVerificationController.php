<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\Jewellery;
use App\Models\LockerVerification;
use App\Models\LockerVerificationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LockerVerificationController extends Controller
{
    public function index()
    {
        $verifications = LockerVerification::with(['locker', 'verifiedBy', 'jewelleryItems'])
            ->latest()
            ->paginate(10);

        return view('locker-verification.index', compact('verifications'));
    }

    public function beforeStorage()
    {
        $lockers = Locker::where('status', 'available')->orderBy('locker_number')->get();
        $jewellery = Jewellery::where('status', 'available')->orderBy('name')->get();

        return view('locker-verification.before', compact('lockers', 'jewellery'));
    }

    public function storeBeforeStorage(Request $request)
    {
        $validated = $request->validate([
            'locker_id' => 'required|exists:lockers,id',
            'jewellery_ids' => 'required|array|min:1',
            'jewellery_ids.*' => 'exists:jewellery,id',
            'before_notes' => 'nullable|string',
            'before_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        if ($request->hasFile('before_image')) {
            $path = $request->file('before_image')->store('verifications', 'public');
            $validated['before_image'] = $path;
        }

        $verification = LockerVerification::create([
            'locker_id' => $validated['locker_id'],
            'verified_by' => Auth::id(),
            'before_notes' => $validated['before_notes'] ?? null,
            'before_image' => $validated['before_image'] ?? null,
            'status' => 'pending',
        ]);

        foreach ($validated['jewellery_ids'] as $jewelleryId) {
            LockerVerificationItem::create([
                'verification_id' => $verification->id,
                'jewellery_id' => $jewelleryId,
            ]);

            Jewellery::where('id', $jewelleryId)->update(['status' => 'in_locker']);
        }

        Locker::where('id', $validated['locker_id'])->update(['status' => 'occupied']);

        return redirect()->route('locker-verification.after', $verification->id)
            ->with('success', 'Before-storage verification recorded. Now complete after-storage verification.');
    }

    public function afterStorage(LockerVerification $verification)
    {
        if ($verification->status !== 'pending') {
            return redirect()->route('locker-verification.results', $verification->id)
                ->with('error', 'This verification has already been completed.');
        }

        $verification->load(['locker', 'jewelleryItems']);

        return view('locker-verification.after', compact('verification'));
    }

    public function storeAfterStorage(Request $request, LockerVerification $verification)
    {
        if ($verification->status !== 'pending') {
            return redirect()->route('locker-verification.results', $verification->id)
                ->with('error', 'This verification has already been completed.');
        }

        $validated = $request->validate([
            'after_notes' => 'nullable|string',
            'after_image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'result_status' => 'required|in:pass,fail,flagged',
        ]);

        if ($request->hasFile('after_image')) {
            $path = $request->file('after_image')->store('verifications', 'public');
            $validated['after_image'] = $path;
        }

        $verification->update([
            'after_notes' => $validated['after_notes'] ?? null,
            'after_image' => $validated['after_image'],
            'status' => $validated['result_status'],
            'completed_at' => now(),
        ]);

        return redirect()->route('locker-verification.results', $verification->id)
            ->with('success', 'Locker verification completed successfully');
    }

    public function results(LockerVerification $verification)
    {
        $verification->load(['locker', 'verifiedBy', 'jewelleryItems']);

        return view('locker-verification.results', compact('verification'));
    }
}
