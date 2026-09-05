<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPurchase extends Model
{
    protected $fillable = [
        'travel_request_id',
        'ticket_number',
        'vendor_name',
        'pnr_code',
        'amount',
        'status',
        'boarding_pass_url',
        'boarding_pass_uploaded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'boarding_pass_uploaded_at' => 'datetime',
    ];

    public function travelRequest()
    {
        return $this->belongsTo(TravelRequest::class, 'travel_request_id');
    }
}
