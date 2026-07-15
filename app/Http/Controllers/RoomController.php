<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $query = Room::with('property');
        if (Auth::user()->role === 'owner') {
            $query->whereHas('property', function($q) {
                $q->where('owner_id', Auth::id());
            });
        }
        $rooms = $query->latest()->get();

        return view('room.index', [
            'title' => 'Data Kamar',
            'rooms' => $rooms,
        ]);
    }

    public function create()
    {
        $propertyQuery = Property::query();
        if (Auth::user()->role === 'owner') {
            $propertyQuery->where('owner_id', Auth::id());
        }
        $properties = $propertyQuery->get();
        $facilities = Facility::all();

        return view('room.create', [
            'title' => 'Tambah Kamar',
            'properties' => $properties,
            'facilities' => $facilities,
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        // check property owner
        $property = Property::findOrFail($request->property_id);
        if (Auth::user()->role === 'owner' && $property->owner_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $validate['image'] = $request->file('image')->store('rooms', 'public');
            }
            
            $validate['available_stock'] = $validate['quantity']; // initially available stock = total quantity
            
            $room = Room::create($validate);
            
            if ($request->has('facilities')) {
                $room->facilities()->attach($request->facilities);
            }
            
            DB::commit();
            return redirect()->route('room.index')->with('success', 'Kamar berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan kamar: ' . $e->getMessage());
        }
    }

    public function show(Room $room)
    {
        if (Auth::user()->role === 'owner' && $room->property->owner_id !== Auth::id()) {
            abort(403);
        }
        return view('room.show', [
            'title' => 'Detail Kamar',
            'room' => $room,
        ]);
    }

    public function edit(Room $room)
    {
        if (Auth::user()->role === 'owner' && $room->property->owner_id !== Auth::id()) {
            abort(403);
        }
        
        $propertyQuery = Property::query();
        if (Auth::user()->role === 'owner') {
            $propertyQuery->where('owner_id', Auth::id());
        }
        $properties = $propertyQuery->get();
        $facilities = Facility::all();
        
        return view('room.edit', [
            'title' => 'Edit Kamar',
            'room' => $room,
            'properties' => $properties,
            'facilities' => $facilities,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        if (Auth::user()->role === 'owner' && $room->property->owner_id !== Auth::id()) {
            abort(403);
        }

        $validate = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'available_stock' => 'required|integer|min:0|max:'.$request->quantity,
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        $property = Property::findOrFail($request->property_id);
        if (Auth::user()->role === 'owner' && $property->owner_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                if ($room->image) {
                    Storage::disk('public')->delete($room->image);
                }
                $validate['image'] = $request->file('image')->store('rooms', 'public');
            }
            
            $room->update($validate);
            
            if ($request->has('facilities')) {
                $room->facilities()->sync($request->facilities);
            } else {
                $room->facilities()->detach();
            }
            
            DB::commit();
            return redirect()->route('room.index')->with('success', 'Kamar berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengubah kamar');
        }
    }

    public function destroy(Room $room)
    {
        if (Auth::user()->role === 'owner' && $room->property->owner_id !== Auth::id()) {
            abort(403);
        }
        DB::beginTransaction();
        try {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $room->facilities()->detach();
            $room->delete();
            DB::commit();
            return redirect()->route('room.index')->with('success', 'Kamar berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus kamar');
        }
    }
}
