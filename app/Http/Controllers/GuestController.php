<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Reservation;

/**
 * Dashboard publik (read-only) untuk pengunjung tanpa login.
 *
 * Tamu hanya dapat melihat reservasi berstatus "approved" dan data pribadi
 * pemesan (nama, NIM, no. WA) tidak pernah diambil dari database.
 */
class GuestController extends Controller
{
    /**
     * Kolom reservasi yang aman ditampilkan ke publik.
     */
    private const PUBLIC_RESERVATION_COLUMNS = [
        'id',
        'facility_id',
        'start_time',
        'end_time',
        'status',
        'activity_name',
        'organization',
        'participant_count',
    ];

    public function index()
    {
        $facilities = Facility::all();

        $upcomingReservations = $this->approvedReservations()
            ->where('end_time', '>=', now())
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $facilityCount = $facilities->count();
        $approvedCount = Reservation::where('status', 'approved')->count();
        $todayCount = Reservation::where('status', 'approved')->whereDate('start_time', today())->count();

        return view('guest.dashboard', compact('facilities', 'upcomingReservations', 'facilityCount', 'approvedCount', 'todayCount'));
    }

    public function reservations(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date'],
            'facility' => ['nullable', 'integer', 'exists:facilities,id'],
        ]);

        $query = $this->approvedReservations()->orderByDesc('start_time');

        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        if ($request->filled('facility')) {
            $query->where('facility_id', $request->facility);
        }

        $reservations = $query->get();
        $facilities = Facility::orderBy('name')->get(['id', 'name']);

        return view('guest.reservations', compact('reservations', 'facilities'));
    }

    public function facilities(Request $request)
    {
        $query = Facility::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $facilities = $query->get();

        return view('guest.facilities', compact('facilities'));
    }

    private function approvedReservations()
    {
        return Reservation::select(self::PUBLIC_RESERVATION_COLUMNS)
            ->with('facility:id,name,location')
            ->where('status', 'approved');
    }
}
