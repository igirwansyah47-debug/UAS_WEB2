<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    public function index()
    {
        return view('facility.index', [
            'title' => 'Master Fasilitas',
            'facilities' => Facility::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('facility.create', [
            'title' => 'Tambah Fasilitas',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            Facility::create($validate);
            DB::commit();
            return redirect()->route('facility.index')->with('success', 'Fasilitas berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan fasilitas');
        }
    }

    public function edit(Facility $facility)
    {
        return view('facility.edit', [
            'title' => 'Edit Fasilitas',
            'facility' => $facility,
        ]);
    }

    public function update(Request $request, Facility $facility)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $facility->update($validate);
            DB::commit();
            return redirect()->route('facility.index')->with('success', 'Fasilitas berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengubah fasilitas');
        }
    }

    public function destroy(Facility $facility)
    {
        DB::beginTransaction();
        try {
            $facility->delete();
            DB::commit();
            return redirect()->route('facility.index')->with('success', 'Fasilitas berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus fasilitas');
        }
    }
}
