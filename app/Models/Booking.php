<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'room_id', 'start_date', 'end_date', 'duration_months', 'total_price', 'status', 'snap_token', 'security_deposit', 'is_deposit_returned'];

    protected $casts = [
        'is_deposit_returned' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function extraBills()
    {
        return $this->hasMany(ExtraBill::class);
    }
}
