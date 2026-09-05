<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    protected $fillable = [
        'travel_ref',
        'traveler_name',
        'destination',
        'purpose',
        'start_date',
        'end_date',
        'airline',
        'flight_number',
        'ticket_cost',
        'status',
        'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'ticket_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->hasOne(TicketPurchase::class, 'travel_request_id');
    }

    public function vendorPayment()
    {
        return $this->hasOne(VendorPayment::class, 'travel_request_id');
    }
}
