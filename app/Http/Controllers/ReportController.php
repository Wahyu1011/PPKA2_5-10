<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $reports = Report::with(['user', 'facility'])->latest()->get();
        } else {
            $reports = Report::with('facility')->where('user_id', Auth::id())->latest()->get();
        }
        return view('reports', compact('reports'));
    }

    public function create(Request $request)
    {
        $facilities = Facility::all();
        $selectedFacility = $request->query('facility_id');
        return view('reports_create', compact('facilities', 'selectedFacility'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'new';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        unset($validated['image']);

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'resolution_note' => 'nullable|string'
        ]);

        $report->update([
            'status' => $request->status,
            'resolution_note' => $request->resolution_note
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
