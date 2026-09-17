@extends('layouts.dashboard')

@section('title', isset($facility) ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru')

@section('dashboard_content')
    <div class="glass-panel" style="max-width: 600px; margin: 0 auto;">
        <div class="section-header">
            <h2>{{ isset($facility) ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}</h2>
            <a href="{{ route('facilities.index') }}" class="view-all"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        
        <form method="POST" action="{{ isset($facility) ? route('facilities.update', $facility) : route('facilities.store') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf
            @if(isset($facility))
                @method('PUT')
            @endif

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="name" style="font-weight: 500;">Nama Fasilitas</label>
                <input type="text" id="name" name="name" value="{{ old('name', $facility->name ?? '') }}" required
                       style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.8rem; border-radius: 8px; color: white; outline: none;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="location" style="font-weight: 500;">Lokasi (Gedung/Area)</label>
                <input type="text" id="location" name="location" value="{{ old('location', $facility->location ?? '') }}" required
                       style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.8rem; border-radius: 8px; color: white; outline: none;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="description" style="font-weight: 500;">Deskripsi & Kegunaan</label>
                <textarea id="description" name="description" rows="4" required
                          style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.8rem; border-radius: 8px; color: white; outline: none; resize: vertical;">{{ old('description', $facility->description ?? '') }}</textarea>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="image" style="font-weight: 500;">Unggah Gambar Fasilitas</label>
                @if(isset($facility) && $facility->image_url)
                    <div style="margin-bottom: 0.5rem;">
                        <img src="{{ $facility->image_url }}" alt="Preview" style="max-height: 120px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem;">Gambar saat ini. Biarkan kosong jika tidak ingin mengubah.</p>
                    </div>
                @endif
                <input type="file" id="image" name="image" accept="image/*" {{ isset($facility) ? '' : 'required' }}
                       style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.8rem; border-radius: 8px; color: white; outline: none;">
                <small style="color: var(--text-muted);">Format: JPG, PNG (Maksimal 2MB)</small>
            </div>

            <button type="submit" class="btn-reserve" style="margin-top: 1rem; padding: 1rem;">
                <i class="fas fa-save"></i> Simpan Data Fasilitas
            </button>
        </form>
    </div>
@endsection
