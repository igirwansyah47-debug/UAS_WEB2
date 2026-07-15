<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Validate tenant has rented this property (active or completed booking)
        $hasBooked = Booking::where('tenant_id', Auth::id())
            ->whereHas('room', function($q) use ($request) {
                $q->where('property_id', $request->property_id);
            })
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        if (!$hasBooked) {
            return redirect()->back()->with('error', 'Anda hanya bisa memberikan ulasan untuk kos yang pernah atau sedang disewa.');
        }

        // Check if already reviewed
        $existingReview = Review::where('tenant_id', Auth::id())
            ->where('property_id', $request->property_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk properti ini.');
        }

        Review::create([
            'tenant_id' => Auth::id(),
            'property_id' => $request->property_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
