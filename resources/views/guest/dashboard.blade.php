@extends('layouts.guest')

@section('title', 'CampusRes | Reservasi Fasilitas Kampus')

@section('guest_content')
    <div class="welcome-section">
        <h1>Selamat Datang di CampusRes! 👋</h1>
        <p>Lihat fasilitas kampus dan jadwal pemakaiannya. <a href="{{ route('login') }}" class="view-all">Masuk</a> untuk membuat reservasi.</p>
    </div>

    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-icon icon-success"><i class="fas fa-building"></i></div>
            <div class="stat-info">
                <h3>{{ $facilityCount }}</h3>
                <p>Fasilitas Tersedia</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-primary"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $approvedCount }}</h3>
                <p>Reservasi Disetujui</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-warning"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-info">
                <h3>{{ $todayCount }}</h3>
                <p>Terpakai Hari Ini</p>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="facilities-section glass-panel">
            <div class="section-header">
                <h2>Sorotan Fasilitas</h2>
                <a href="{{ route('guest.facilities') }}" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="facility-cards" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                @forelse($facilities->take(2) as $facility)
                <div class="facility-card">
                    <div class="facility-img" style="background-image: url('{{ asset($facility->image_url) }}')"></div>
                    <div class="facility-info">
                        <h4>{{ $facility->name }}</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.3rem;"><i class="fas fa-map-marker-alt"></i> {{ $facility->location }}</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $facility->description }}</p>
                    </div>
                </div>
                @empty
                <p style="color: var(--text-muted);">Belum ada fasilitas.</p>
                @endforelse
            </div>
        </div>

        <div class="recent-reservations glass-panel">
            <div class="section-header">
                <h2>Jadwal Terdekat</h2>
                <a href="{{ route('guest.reservations') }}" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <ul class="reservation-list">
                @forelse($upcomingReservations as $res)
                <li class="reservation-item" style="padding: 0.8rem;">
                    <div class="res-details">
                        <h4 style="font-size: 0.9rem; margin-bottom: 0.2rem;">{{ Str::limit($res->facility->name, 20) }}</h4>
                        <p style="font-size: 0.75rem;"><i class="fas fa-clock"></i> {{ $res->start_time->format('d M, H:i') }} - {{ $res->end_time->format('H:i') }}</p>
                    </div>
                    <span class="status status-approved" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">Terpakai</span>
                </li>
                @empty
                <li class="reservation-item" style="justify-content: center; padding: 1.5rem;">
                    <p style="color: var(--text-muted); text-align: center; width: 100%; font-size:0.85rem;">Belum ada jadwal terdekat.</p>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
