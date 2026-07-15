<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index()
    {
        $query = Complaint::with(['room.property', 'tenant']);
        $user = Auth::user();

        if ($user->role === 'tenant') {
            $query->where('tenant_id', $user->id);
        } elseif ($user->role === 'owner') {
            $query->whereHas('room.property', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        }

        return view('complaint.index', [
            'title' => 'Daftar Komplain',
            'complaints' => $query->latest()->get(),
        ]);
    }

    public function create(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $room = Room::with('property')->findOrFail($request->room_id);

        return view('complaint.create', [
            'title' => 'Ajukan Komplain',
            'room' => $room,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $validate = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validate['image'] = $request->file('image')->store('complaints', 'public');
        }

        $validate['tenant_id'] = Auth::id();
        $validate['status'] = 'open';

        Complaint::create($validate);

        return redirect()->route('complaint.index')->with('success', 'Komplain berhasil diajukan.');
    }

    public function show(Complaint $complaint)
    {
        return view('complaint.show', [
            'title' => 'Detail Komplain',
            'complaint' => $complaint,
        ]);
    }

    public function update(Request $request, Complaint $complaint)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $complaint->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status komplain berhasil diubah.');
    }
}
