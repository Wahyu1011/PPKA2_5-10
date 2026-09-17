<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Reservasi berhasil diajukan dan menunggu persetujuan.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
