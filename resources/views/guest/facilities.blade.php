@extends('layouts.guest')

@section('title', 'Daftar Fasilitas | Reservasi Fasilitas Kampus')

@section('guest_content')
    <div class="facilities-section glass-panel">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Daftar Fasilitas</h2>
            @if(request('search'))
                <a href="{{ route('guest.facilities') }}" style="color: #fca5a5; text-decoration: none; font-size: 0.85rem;">Reset pencarian "{{ request('search') }}"</a>
            @endif
        </div>

        <div class="facility-cards">
            @forelse($facilities as $facility)
            <div class="facility-card">
                <div class="facility-img" style="background-image: url('{{ asset($facility->image_url) }}')"></div>
                <div class="facility-info">
                    <h4 style="display: flex; align-items: center; justify-content: space-between;">
                        {{ $facility->name }}
                        @if($facility->status == 'maintenance')
                            <span class="status status-danger" style="font-size: 0.6rem; padding: 0.2rem 0.5rem; border-radius: 4px;">Perbaikan</span>
                        @endif
                    </h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.2rem;"><i class="fas fa-map-marker-alt"></i> {{ $facility->location }}</p>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-tag"></i> {{ $facility->type ?? 'Umum' }} &nbsp;&bull;&nbsp; <i class="fas fa-users"></i> {{ $facility->capacity ?? '-' }} Orang</p>
                    <p style="font-size: 0.8rem; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 1rem;">{{ $facility->description }}</p>
                    <div style="display: flex; gap: 0.5rem; margin-top: auto;">
                        <a href="{{ route('guest.reservations', ['facility' => $facility->id]) }}" class="btn-reserve" style="display: block; text-align: center; text-decoration: none; flex: 1;">Lihat Jadwal</a>
                        <a href="{{ route('login') }}" class="btn-action btn-primary" style="flex: 1; padding: 0.6rem; font-size: 0.85rem;">Masuk untuk Reservasi</a>
                    </div>
                </div>
            </div>
            @empty
            <p style="color: var(--text-muted);">Tidak ada fasilitas yang cocok.</p>
            @endforelse
        </div>
    </div>
@endsection
