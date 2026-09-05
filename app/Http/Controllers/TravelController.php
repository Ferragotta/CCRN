<?php

namespace App\Http\Controllers;

use App\Models\TicketPurchase;
use App\Models\TravelRequest;
use App\Models\VendorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $requests = TravelRequest::with(['ticket', 'vendorPayment', 'user'])->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($requests);
        }

        return view('modules.travel', [
            'headerTitle' => 'Official Travel & Boarding Pass Verification',
            'travelRequests' => $requests,
        ]);
    }
}
