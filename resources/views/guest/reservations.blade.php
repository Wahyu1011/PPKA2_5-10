@extends('layouts.guest')

@section('title', 'Jadwal Reservasi | Reservasi Fasilitas Kampus')

@section('guest_content')
    <div class="recent-reservations glass-panel">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h2>Jadwal Reservasi Fasilitas</h2>

            <form method="GET" action="{{ route('guest.reservations') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                <select name="facility" style="background: var(--input-bg); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 8px; color: var(--text-main); outline: none;">
                    <option value="">Semua Fasilitas</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" @selected(request('facility') == $facility->id)>{{ $facility->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" style="background: var(--input-bg); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 8px; color: var(--text-main); outline: none;">
                <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer;">Filter</button>
                @if(request('date') || request('facility'))
                    <a href="{{ route('guest.reservations') }}" style="color: #fca5a5; text-decoration: none; font-size: 0.85rem; margin-left: 0.5rem;">Reset</a>
                @endif
            </form>
        </div>

        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
            <i class="fas fa-info-circle"></i> Hanya menampilkan reservasi yang telah disetujui admin.
        </p>

        <ul class="reservation-list">
            @forelse($reservations as $res)
            <li class="reservation-item" style="flex-direction: column; align-items: stretch;">
                <div class="res-details" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h4>{{ $res->facility->name }}</h4>
                            <p><i class="fas fa-clock"></i> {{ $res->start_time->format('d M Y, H:i') }} - {{ $res->end_time->format('H:i') }}</p>
                            <p style="margin-top: 0.3rem;"><i class="fas fa-map-marker-alt"></i> {{ $res->facility->location }}</p>
                        </div>
                        @if($res->end_time->isPast())
                            <span class="status status-primary">Selesai</span>
                        @else
                            <span class="status status-approved">Disetujui</span>
                        @endif
                    </div>

                    @if($res->activity_name)
                    <div style="background: var(--input-bg); padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); margin-top: 0.8rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.8rem;">
                        <div>
                            <strong style="color: var(--text-muted);">Kegiatan:</strong> {{ $res->activity_name }}
                        </div>
                        <div>
                            <strong style="color: var(--text-muted);">Penyelenggara:</strong> {{ $res->organization ?: '-' }}<br>
                            <strong style="color: var(--text-muted);">Peserta:</strong> {{ $res->participant_count ?? '-' }} orang
                        </div>
                    </div>
                    @endif
                </div>
            </li>
            @empty
            <li class="reservation-item" style="justify-content: center;">
                <p style="color: var(--text-muted); text-align: center; width: 100%;">Belum ada reservasi yang disetujui.</p>
            </li>
            @endforelse
        </ul>
    </div>
@endsection
