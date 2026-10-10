@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CampusRes</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('guest.dashboard') }}" class="nav-item {{ request()->routeIs('guest.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> <span>Dashboard</span></a>
                <a href="{{ route('guest.reservations') }}" class="nav-item {{ request()->routeIs('guest.reservations') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Jadwal Reservasi</span></a>
                <a href="{{ route('guest.facilities') }}" class="nav-item {{ request()->routeIs('guest.facilities') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Fasilitas</span></a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <form action="{{ route('guest.facilities') }}" method="GET" class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari fasilitas..." value="{{ request('search') }}">
                </form>
                <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                    <!-- Toggle Theme -->
                    <button id="themeToggleBtn" title="Ganti Tema" style="border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 50%; background: rgba(255,255,255,0.05); transition: background 0.3s; margin-right: 0.5rem;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                    <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.55rem 1.1rem; border-radius: 20px; background: var(--primary-hover); color: #ffffff; font-weight: 600; font-size: 0.9rem; text-decoration: none; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35); transition: filter 0.3s;" onmouseover="this.style.filter='brightness(0.9)'" onmouseout="this.style.filter='none'">
                        <i class="fas fa-sign-in-alt"></i> <span style="color: #ffffff;">Masuk / Daftar</span>
                    </a>
                </div>
            </header>

            <div class="dashboard-content">
                @if($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('guest_content')
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard.js') }}?v={{ time() }}"></script>
@endpush
