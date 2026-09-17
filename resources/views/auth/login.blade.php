@extends('layouts.app')

@section('title', 'Login | Sistem Pendaftaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
@endpush

@section('content')
    <div class="login-container">
        <div class="login-header">
            <h1>Selamat Datang</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            <div class="input-group">
                <label for="username">Username / Email</label>
                <input type="text" id="username" name="email" placeholder="Masukkan username/email Anda" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
            </div>
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Ingat Saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password">Lupa Password?</a>
                @endif
            </div>
            <button type="submit" class="login-btn">
                <span class="btn-text">Masuk</span>
            </button>
            <div class="error-message @if($errors->any()) show @endif">
                @if($errors->any())
                    {{ $errors->first() }}
                @endif
            </div>
        </form>
        <div class="login-footer">
            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        </div>
    </div>
@endsection
