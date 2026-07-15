<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function index()
    {
        $properties = Property::with('owner')->latest()->get();

        return view('verification.index', [
            'title' => 'Verifikasi Properti',
            'properties' => $properties,
        ]);
    }

    public function approve(Property $property)
    {
        DB::beginTransaction();
        try {
            $property->update(['is_verified' => true]);
            DB::commit();
            return redirect()->route('verification.index')->with('success', 'Properti "' . $property->name . '" berhasil diverifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memverifikasi properti: ' . $e->getMessage());
        }
    }

    public function reject(Property $property)
    {
        DB::beginTransaction();
        try {
            $property->update(['is_verified' => false]);
            DB::commit();
            return redirect()->route('verification.index')->with('success', 'Verifikasi properti "' . $property->name . '" berhasil dicabut.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mencabut verifikasi properti: ' . $e->getMessage());
        }
    }
}
