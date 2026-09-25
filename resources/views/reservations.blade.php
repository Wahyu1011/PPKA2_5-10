@extends('layouts.dashboard')

@section('title', 'Semua Reservasi | Reservasi Fasilitas Kampus')

@section('dashboard_content')
    <div class="recent-reservations glass-panel">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h2>{{ Auth::user()->role === 'admin' ? 'Semua Reservasi' : 'Reservasi Saya' }}</h2>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('reservations.export') }}" class="btn-reserve" style="width: auto; padding: 0.5rem 1rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4);">
                    <i class="fas fa-file-csv"></i> Ekspor CSV
                </a>
            @endif
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
                    <div class="res-details" style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <h4>{{ $res->facility->name }}</h4>
                                <p>{{ \Carbon\Carbon::parse($res->start_time)->format('d M Y, H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}</p>
                                @if(Auth::user()->role === 'admin')
                                    <p style="color: #cbd5e1; font-size: 0.85rem; margin-top: 0.3rem;"><i class="fas fa-user"></i> {{ $res->user->name }}</p>
                                @endif
                            </div>
                            <span class="status status-{{ $res->status == 'approved' ? 'approved' : ($res->status == 'rejected' || $res->status == 'cancelled' ? 'danger' : 'pending') }}" 
                                  style="{{ $res->status == 'rejected' || $res->status == 'cancelled' ? 'background: rgba(239, 68, 68, 0.15); color: #fca5a5;' : '' }}">
                                {{ ucfirst($res->status) }}
                            </span>
                        </div>

                        @if($res->cancellation_reason)
                            <div style="background: rgba(239, 68, 68, 0.1); padding: 0.5rem 0.8rem; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.3); margin-top: 0.5rem; font-size: 0.8rem; color: #fca5a5;">
                                <strong>Alasan Batal:</strong> {{ $res->cancellation_reason }}
                            </div>
                        @endif

                        @if($res->activity_name)
                        <div style="background: rgba(15,23,42,0.3); padding: 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); margin-top: 0.8rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.8rem;">
                            <div>
                                <strong style="color: #cbd5e1;">Kegiatan:</strong> {{ $res->activity_name }}<br>
                                <strong style="color: #cbd5e1;">Peserta:</strong> {{ $res->participant_count }} orang
                            </div>
                            <div>
                                <strong style="color: #cbd5e1;">Identitas:</strong> {{ $res->nim }}<br>
                                <strong style="color: #cbd5e1;">Prodi/UKM:</strong> {{ $res->department }} {{ $res->organization ? ' - ' . $res->organization : '' }}<br>
                                <strong style="color: #cbd5e1;">No. WA:</strong> {{ $res->phone }}
                            </div>
                        </div>
                        @endif
                    </div>
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

                @if(Auth::user()->role === 'admin' && $res->status === 'approved' && \Carbon\Carbon::parse($res->start_time)->isFuture())
                <div style="margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
                    <form method="POST" action="{{ route('reservations.adminCancel', $res) }}" style="display: flex; gap: 0.5rem; align-items: center;" onsubmit="return confirm('Yakin membatalkan reservasi ini secara paksa?');">
                        @csrf
                        <input type="text" name="cancellation_reason" placeholder="Alasan pembatalan mendesak..." required style="flex: 1; padding: 0.3rem 0.5rem; border-radius: 5px; background: rgba(15,23,42,0.5); border: 1px solid var(--border-color); color: white; font-size: 0.8rem;">
                        <button type="submit" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); padding: 0.3rem 0.6rem; border-radius: 5px; cursor: pointer; font-size: 0.8rem; white-space: nowrap;">Batalkan (Darurat)</button>
                    </form>
                </div>
                @endif

                @if(Auth::user()->role === 'user' && !in_array($res->status, ['cancelled', 'rejected']) && \Carbon\Carbon::parse($res->start_time)->isFuture())
                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
                    <form method="POST" action="{{ route('reservations.cancel', $res) }}" onsubmit="return confirm('Yakin membatalkan reservasi ini?');">
                        @csrf
                        <button type="submit" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); padding: 0.3rem 0.6rem; border-radius: 5px; cursor: pointer; font-size: 0.8rem;">Batalkan Reservasi</button>
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
