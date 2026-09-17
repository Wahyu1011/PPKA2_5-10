@extends('layouts.dashboard')

@section('title', 'Semua Reservasi | Reservasi Fasilitas Kampus')

@section('dashboard_content')
    <div class="recent-reservations glass-panel">
        <div class="section-header">
            <h2>{{ Auth::user()->role === 'admin' ? 'Semua Reservasi' : 'Reservasi Saya' }}</h2>
        </div>
        <ul class="reservation-list">
            @php
                $reservations = Auth::user()->role === 'admin' 
                    ? \App\Models\Reservation::with(['facility', 'user'])->latest()->get()
                    : \App\Models\Reservation::with('facility')->where('user_id', Auth::id())->latest()->get();
            @endphp
            @forelse($reservations as $res)
            <li class="reservation-item" style="flex-direction: column; align-items: stretch;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <div class="res-details">
                        <h4>{{ $res->facility->name }}</h4>
                        <p>{{ \Carbon\Carbon::parse($res->start_time)->format('d M Y, H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}</p>
                        @if(Auth::user()->role === 'admin')
                            <p style="color: #cbd5e1; font-size: 0.8rem; margin-top: 0.3rem;"><i class="fas fa-user"></i> {{ $res->user->name }}</p>
                        @endif
                    </div>
                    <span class="status status-{{ $res->status == 'approved' ? 'approved' : ($res->status == 'rejected' ? 'danger' : 'pending') }}" 
                          style="{{ $res->status == 'rejected' ? 'background: rgba(239, 68, 68, 0.15); color: #fca5a5;' : '' }}">
                        {{ ucfirst($res->status) }}
                    </span>
                </div>
                @if(Auth::user()->role === 'admin' && $res->status === 'pending')
                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
                    <form method="POST" action="{{ route('reservations.updateStatus', $res) }}">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); padding: 0.3rem 0.6rem; border-radius: 5px; cursor: pointer; font-size: 0.8rem;">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('reservations.updateStatus', $res) }}">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); padding: 0.3rem 0.6rem; border-radius: 5px; cursor: pointer; font-size: 0.8rem;">Tolak</button>
                    </form>
                </div>
                @endif
            </li>
            @empty
            <li class="reservation-item" style="justify-content: center;">
                <p style="color: var(--text-muted); text-align: center; width: 100%;">Belum ada reservasi.</p>
            </li>
            @endforelse
        </ul>
    </div>
@endsection
