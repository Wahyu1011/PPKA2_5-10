<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();
        return view('facilities', compact('facilities'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('facilities_form');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $path = $request->file('image')->store('facilities', 'public');
        $validated['image_url'] = Storage::url($path);
        unset($validated['image']);

        Facility::create($validated);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function edit(Facility $facility)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('facilities_form', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('facilities', 'public');
            $validated['image_url'] = Storage::url($path);
            
            // Delete old image if it's from local storage
            if (str_starts_with($facility->image_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $facility->image_url);
                Storage::disk('public')->delete($oldPath);
            }
        }
        unset($validated['image']);

        $facility->update($validated);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy(Facility $facility)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        // Delete image if it's from local storage
        if (str_starts_with($facility->image_url, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $facility->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}
