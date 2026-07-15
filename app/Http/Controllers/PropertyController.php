<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $query = Property::query();
        if (Auth::user()->role === 'owner') {
            $query->where('owner_id', Auth::id());
        }
        $properties = $query->latest()->get();

        return view('property.index', [
            'title' => 'Data Properti',
            'properties' => $properties,
        ]);
    }

    public function create()
    {
        return view('property.create', [
            'title' => 'Tambah Properti',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $validate['image'] = $request->file('image')->store('properties', 'public');
            }
            $validate['owner_id'] = Auth::id();
            Property::create($validate);
            DB::commit();
            return redirect()->route('property.index')->with('success', 'Properti berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan properti: ' . $e->getMessage());
        }
    }

    public function show(Property $property)
    {
        if (Auth::user()->role === 'owner' && $property->owner_id !== Auth::id()) {
            abort(403);
        }
        return view('property.show', [
            'title' => 'Detail Properti',
            'property' => $property,
        ]);
    }

    public function edit(Property $property)
    {
        if (Auth::user()->role === 'owner' && $property->owner_id !== Auth::id()) {
            abort(403);
        }
        return view('property.edit', [
            'title' => 'Edit Properti',
            'property' => $property,
        ]);
    }

    public function update(Request $request, Property $property)
    {
        if (Auth::user()->role === 'owner' && $property->owner_id !== Auth::id()) {
            abort(403);
        }
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                if ($property->image) {
                    Storage::disk('public')->delete($property->image);
                }
                $validate['image'] = $request->file('image')->store('properties', 'public');
            }
            $property->update($validate);
            DB::commit();
            return redirect()->route('property.index')->with('success', 'Properti berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengubah properti');
        }
    }

    public function destroy(Property $property)
    {
        if (Auth::user()->role === 'owner' && $property->owner_id !== Auth::id()) {
            abort(403);
        }
        DB::beginTransaction();
        try {
            if ($property->image) {
                Storage::disk('public')->delete($property->image);
            }
            $property->delete();
            DB::commit();
            return redirect()->route('property.index')->with('success', 'Properti berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus properti');
        }
    }
}
