@extends('layouts.dashboard')

@section('title', 'Daftar Laporan | CampusRes')

@section('dashboard_content')
<div class="welcome-section mb-4">
    <h1>Laporan Kerusakan Fasilitas</h1>
    <p>Kelola dan pantau status laporan kerusakan fasilitas kampus.</p>
</div>

<div class="glass-panel">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Daftar Laporan</h2>
        @if(Auth::user()->role === 'user')
        <a href="{{ route('reports.create') }}" class="btn-reserve" style="width: auto; padding: 0.5rem 1rem; text-decoration: none;">
            <i class="fas fa-plus"></i> Buat Laporan Baru
        </a>
        @endif
    </div>

    @if($reports->isEmpty())
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Belum ada laporan kerusakan.</p>
    @else
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($reports as $rep)
            <div style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px; display: flex; gap: 1.5rem; flex-wrap: wrap;">
                
                @if($rep->image_url)
                <div style="width: 150px; height: 150px; flex-shrink: 0;">
                    <img src="{{ $rep->image_url }}" alt="Foto Kerusakan" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                </div>
                @endif
                
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <h3 style="margin: 0; font-size: 1.2rem; color: white;">{{ $rep->facility->name }} - {{ $rep->category }}</h3>
                        
                        @php
                            $badgeColor = match($rep->status) {
                                'new' => ['bg' => 'rgba(59, 130, 246, 0.2)', 'text' => '#60a5fa', 'label' => 'Baru'],
                                'processing' => ['bg' => 'rgba(245, 158, 11, 0.2)', 'text' => '#fbbf24', 'label' => 'Diproses'],
                                'completed' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'text' => '#34d399', 'label' => 'Selesai'],
                                'rejected' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'text' => '#fca5a5', 'label' => 'Ditolak'],
                                default => ['bg' => 'rgba(255,255,255,0.1)', 'text' => '#fff', 'label' => 'Unknown']
                            };
                        @endphp
                        <span style="background: {{ $badgeColor['bg'] }}; color: {{ $badgeColor['text'] }}; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            {{ $badgeColor['label'] }}
                        </span>
                    </div>
                    
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">Dilaporkan oleh <strong>{{ $rep->user->name }}</strong> pada {{ \Carbon\Carbon::parse($rep->created_at)->format('d M Y, H:i') }}</p>
                    
                    <div style="background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <p style="margin: 0; font-size: 0.95rem; line-height: 1.5; color: #cbd5e1;">"{{ $rep->description }}"</p>
                    </div>

                    @if($rep->resolution_note)
                        <div style="background: rgba(16, 185, 129, 0.1); border-left: 3px solid #10b981; padding: 0.8rem; margin-bottom: 1rem; border-radius: 4px; font-size: 0.9rem;">
                            <strong style="color: #34d399;">Catatan Penyelesaian:</strong>
                            <p style="margin: 0.3rem 0 0 0; color: #cbd5e1;">{{ $rep->resolution_note }}</p>
                        </div>
                    @endif

                    @if(Auth::user()->role === 'admin' && in_array($rep->status, ['new', 'processing']))
                        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; margin-top: 1rem;">
                            <form method="POST" action="{{ route('reports.updateStatus', $rep) }}" style="display: flex; gap: 0.8rem; align-items: flex-start; flex-wrap: wrap;">
                                @csrf
                                <select name="status" required style="background: rgba(15,23,42,0.8); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 5px; color: white;">
                                    <option value="processing" {{ $rep->status == 'processing' ? 'selected' : '' }}>Tandai Diproses</option>
                                    <option value="completed">Tandai Selesai</option>
                                    <option value="rejected">Tolak Laporan</option>
                                </select>
                                <input type="text" name="resolution_note" placeholder="Catatan opsional (solusi/alasan)..." style="flex: 1; min-width: 200px; background: rgba(15,23,42,0.8); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 5px; color: white;">
                                <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">Update Status</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
