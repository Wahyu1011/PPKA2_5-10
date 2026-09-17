<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $facilities = Facility::all();
        
        if ($user->role === 'admin') {
            $reservations = Reservation::with(['user', 'facility'])->latest()->get();
            $activeCount = Reservation::where('status', 'approved')->count();
            $pendingCount = Reservation::where('status', 'pending')->count();
        } else {
            $reservations = Reservation::with('facility')->where('user_id', $user->id)->latest()->get();
            $activeCount = Reservation::where('user_id', $user->id)->where('status', 'approved')->count();
            $pendingCount = Reservation::where('user_id', $user->id)->where('status', 'pending')->count();
        }

        $facilityCount = $facilities->count();

        return view('dashboard', compact('facilities', 'reservations', 'activeCount', 'pendingCount', 'facilityCount'));
    }
}
