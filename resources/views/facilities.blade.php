@extends('layouts.dashboard')

@section('title', 'Kelola Fasilitas | Reservasi Fasilitas Kampus')

@section('dashboard_content')
    <div class="facilities-section glass-panel">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Daftar Fasilitas</h2>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('facilities.create') }}" class="btn-reserve" style="width: auto; padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                <i class="fas fa-plus"></i> Tambah Fasilitas
            </a>
            @endif
        </div>
        
        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #34d399; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="facility-cards">
            @foreach(\App\Models\Facility::all() as $facility)
            <div class="facility-card">
                <div class="facility-img" style="background-image: url('{{ $facility->image_url }}')"></div>
                <div class="facility-info">
                    <h4>{{ $facility->name }}</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt"></i> {{ $facility->location }}</p>
                    <p style="font-size: 0.8rem; color: #cbd5e1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 1rem;">{{ $facility->description }}</p>
                    @if(Auth::user()->role === 'user')
                    <form method="POST" action="{{ route('reservations.store') }}">
                        @csrf
                        <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                        <input type="hidden" name="start_time" value="{{ now()->addHours(1)->format('Y-m-d H:i:s') }}">
                        <input type="hidden" name="end_time" value="{{ now()->addHours(2)->format('Y-m-d H:i:s') }}">
                        <button type="submit" class="btn-reserve">Reservasi 1 Jam</button>
                    </form>
                    @elseif(Auth::user()->role === 'admin')
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <a href="{{ route('facilities.edit', $facility) }}" style="flex: 1; text-align: center; background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); padding: 0.5rem; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600;"><i class="fas fa-edit"></i> Edit</a>
                        <form method="POST" action="{{ route('facilities.destroy', $facility) }}" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="width: 100%; background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); padding: 0.5rem; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600;"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
