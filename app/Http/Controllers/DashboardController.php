<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = ['title' => 'Dashboard', 'user' => $user];

        if ($user->role === 'superadmin') {
            $data['totalTenants'] = User::where('role', 'tenant')->count();
            $data['totalOwners'] = User::where('role', 'owner')->count();
            $data['totalTransactions'] = Payment::count();
            $data['totalRevenue'] = Payment::where('status', 'paid')->sum('amount');
            
            // Chart Data for last 6 months (SQLite specific date format string)
            $chartData = Payment::where('status', 'paid')
                ->select(
                    DB::raw('sum(amount) as sums'),
                    DB::raw("strftime('%Y-%m', payment_date) as month")
                )
                ->where('payment_date', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            $data['chartLabels'] = $chartData->pluck('month');
            $data['chartValues'] = $chartData->pluck('sums');
        } 
        elseif ($user->role === 'owner') {
            $propertyIds = Property::where('owner_id', $user->id)->pluck('id');
            $rooms = Room::whereIn('property_id', $propertyIds)->get();
            
            $totalRooms = $rooms->count();
            $activeBookingsCount = Booking::whereIn('room_id', $rooms->pluck('id'))
                                          ->where('status', 'active')
                                          ->count();
                                          
            $data['occupancyRate'] = $totalRooms > 0 ? round(($activeBookingsCount / $totalRooms) * 100, 2) : 0;
            
            $data['totalRevenue'] = Payment::where('status', 'paid')
                ->whereHas('booking.room', function($q) use ($propertyIds) {
                    $q->whereIn('property_id', $propertyIds);
                })->sum('amount');
                
            $data['pendingPayments'] = Payment::where('status', 'unpaid')
                ->whereHas('booking.room', function($q) use ($propertyIds) {
                    $q->whereIn('property_id', $propertyIds);
                })->count();

            // Chart Data for last 6 months (SQLite specific date format string)
            $chartData = Payment::where('status', 'paid')
                ->whereHas('booking.room', function($q) use ($propertyIds) {
                    $q->whereIn('property_id', $propertyIds);
                })
                ->select(
                    DB::raw('sum(amount) as sums'),
                    DB::raw("strftime('%Y-%m', payment_date) as month")
                )
                ->where('payment_date', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            $data['chartLabels'] = $chartData->pluck('month');
            $data['chartValues'] = $chartData->pluck('sums');
        }
        elseif ($user->role === 'tenant') {
            $data['activeBookings'] = Booking::with('room.property')
                ->where('tenant_id', $user->id)
                ->where('status', 'active')
                ->get();
                
            $data['pendingPayments'] = Payment::with('booking.room.property')
                ->whereHas('booking', function($q) use ($user) {
                    $q->where('tenant_id', $user->id);
                })
                ->where('status', 'unpaid')
                ->get();
        }

        return view('dashboard.index', $data);
    }

    public function show()
    {
        return view('dashboard.show', [
            'title' => 'My Profile',
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('dashboard.edit', [
            'title' => 'Edit Profile',
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $validate = $request->validate([
                'name' => 'required',
                'password' => 'nullable|min:8',
                'passwordconfirm' => 'nullable|same:password',
                'email' => 'required|email|lowercase|unique:users,email,' . $user->id,
                'avatar' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512'
            ], [
                'name.required' => 'Nama wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'passwordconfirm.same' => 'Konfirmasi password tidak cocok',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'avatar.image' => 'File avatar harus berupa gambar',
                'avatar.mimes' => 'Format avatar harus png, jpg, jpeg, atau svg',
                'avatar.max' => 'Ukuran avatar tidak boleh lebih dari 512 KB',
            ]);

            if ($request->file('avatar')) {
                $validate['avatar'] = $request->file('avatar')->store('img', 'public');
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            if ($request->password) {
                $validate['password'] = bcrypt($request->password);
            } else {
                unset($validate['password']);
            }
            $user->update($validate);

            DB::commit();
            return to_route('dashboard.show')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('dashboard.edit')->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }
}
