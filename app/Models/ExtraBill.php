<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtraBill extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'title', 'amount', 'status', 'snap_token', 'transaction_id', 'payment_date'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
