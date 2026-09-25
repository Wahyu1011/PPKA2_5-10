@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CampusRes</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> <span>Dashboard</span></a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('reservations.index') }}" class="nav-item {{ request()->routeIs('reservations.index') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> <span>Semua Reservasi</span></a>
                    <a href="{{ route('facilities.index') }}" class="nav-item {{ request()->routeIs('facilities.index') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Kelola Fasilitas</span></a>
                    <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="fas fa-exclamation-triangle"></i> <span>Daftar Laporan</span></a>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="fas fa-users"></i> <span>Kelola Pengguna</span></a>
                @else
                    <a href="{{ route('reservations.index') }}" class="nav-item {{ request()->routeIs('reservations.index') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Reservasi Saya</span></a>
                    <a href="{{ route('facilities.index') }}" class="nav-item {{ request()->routeIs('facilities.index') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Fasilitas</span></a>
                    <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="fas fa-exclamation-triangle"></i> <span>Laporan Kerusakan</span></a>
                @endif
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item text-danger" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <form action="{{ route('facilities.index') }}" method="GET" class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari fasilitas..." value="{{ request('search') }}">
                </form>
                <div class="user-profile">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        @php
                            $notifCount = Auth::user()->role === 'admin' 
                                ? \App\Models\Reservation::where('status', 'pending')->count()
                                : \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'approved')->where('start_time', '>=', now())->count();
                        @endphp
                        @if($notifCount > 0)
                            <span class="badge">{{ $notifCount }}</span>
                        @endif
                    </div>
                    <div class="avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="User Avatar">
                    </div>
                    <span>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
                </div>
            </header>

            <div class="dashboard-content">
                @if(session('success'))
                    <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #34d399; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('dashboard_content')
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard.js') }}"></script>
@endpush
