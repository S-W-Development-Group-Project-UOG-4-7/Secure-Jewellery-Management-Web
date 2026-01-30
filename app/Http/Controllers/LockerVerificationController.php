<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\Jewellery;
use App\Models\LockerVerification;
use App\Models\LockerVerificationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LockerVerificationController extends Controller
{
    /**
     * Show the form for "before storage" verification
     */
    public function beforeForm()
    {
        $lockers = Locker::orderBy('id')->get();
        $jewellery = Jewellery::orderBy('name')->get();

        return view('locker_verification.before', compact('lockers', 'jewellery'));
    }

    /**
     * Show the form for "after storage" verification
     */
    public function afterForm()
    {
        $lockers = Locker::orderBy('id')->get();
        $jewellery = Jewellery::orderBy('name')->get();

        return view('locker_verification.after', compact('lockers', 'jewellery'));
    }

    /**
     * Store "before storage" verification
     */
    public function storeBefore(Request $request)
    {
        return $this->storeVerification($request, 'before_storage');
    }

    /**
     * Store "after storage" verification
     */
    public function storeAfter(Request $request)
    {
        return $this->storeVerification($request, 'after_storage');
    }

    /**
     * Shared function to store a verification
     */
    private function storeVerification(Request $request, string $stage)
    {
        $validated = $request->validate([
            'locker_id' => 'required|exists:lockers,id',
            'notes' => 'nullable|string|max:2000',

            'items' => 'required|array|min:1',
            'items.*.jewellery_id' => 'required|exists:jewellery,id',
            'items.*.result' => 'required|in:pass,fail',
            'items.*.remarks' => 'nullable|string|max:500',
            'items.*.proof_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $verification = DB::transaction(function () use ($validated, $stage, $request) {
            $verification = LockerVerification::create([
                'locker_id' => $validated['locker_id'],
                'stage' => $stage,
                'verified_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $index => $item) {
                $proofPath = null;

                if ($request->hasFile("items.$index.proof_image")) {
                    $proofPath = $request->file("items.$index.proof_image")
                        ->store("verification_proofs", "public");
                }

                LockerVerificationItem::create([
                    'locker_verification_id' => $verification->id,
                    'jewellery_id' => $item['jewellery_id'],
                    'result' => $item['result'],
                    'remarks' => $item['remarks'] ?? null,
                    'proof_image' => $proofPath,
                ]);
            }

            return $verification;
        });

        return redirect()->route('locker.verify.results', $verification->id)
            ->with('success', 'Verification saved successfully!');
    }

    /**
     * Show verification results
     */
    public function results(LockerVerification $verification)
    {
        $verification->load([
            'locker',
            'verifier',
            'items.jewellery',
        ]);

        return view('locker_verification.results', compact('verification'));
    }
}
