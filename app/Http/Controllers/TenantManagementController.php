<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;

class TenantManagementController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        $propertyId = $request->input('property_id');
        $status = $request->input('status');

        $ownerPropertyIds = Property::where('owner_id', Auth::id())->pluck('id');
        
        $query = Booking::with(['tenant', 'room.property'])
            ->whereHas('room', function($q) use ($ownerPropertyIds) {
                $q->whereIn('property_id', $ownerPropertyIds);
            });

        if ($propertyId) {
            $query->whereHas('room', function($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['active', 'completed', 'pending']);
        }

        $bookings = $query->latest()->get();
        $properties = Property::where('owner_id', Auth::id())->get();

        return view('tenant_management.index', [
            'title' => 'Manajemen Penghuni',
            'bookings' => $bookings,
            'properties' => $properties,
        ]);
    }

    public function show($id)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        $booking = Booking::with(['tenant', 'room.property', 'payment'])->findOrFail($id);

        if ($booking->room->property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this tenant data.');
        }

        return view('tenant_management.show', [
            'title' => 'Detail Penghuni',
            'booking' => $booking,
        ]);
    }

    public function completeBooking(Booking $booking)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        if ($booking->room->property->owner_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'active') {
            $booking->update(['status' => 'completed']);
            $booking->room->increment('available_stock');
            return redirect()->back()->with('success', 'Status sewa telah diubah menjadi Selesai dan stok kamar dikembalikan.');
        }

        return redirect()->back()->with('error', 'Booking tidak aktif.');
    }
}
