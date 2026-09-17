@extends('layouts.app')

@section('title', 'Daftar | Sistem Pendaftaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
@endpush

@section('content')
    <div class="login-container">
        <div class="login-header">
            <h1>Buat Akun</h1>
            <p>Silakan isi data untuk mendaftar</p>
        </div>
        <form method="POST" action="{{ route('register') }}" class="login-form">
            @csrf
            <div class="input-group">
                <label for="regName">Nama Lengkap</label>
                <input type="text" id="regName" name="name" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="input-group">
                <label for="regEmail">Email</label>
                <input type="email" id="regEmail" name="email" placeholder="Masukkan alamat email" required>
            </div>
            <div class="input-group">
                <label for="regPassword">Password</label>
                <input type="password" id="regPassword" name="password" placeholder="Buat password" required>
            </div>
            <div class="input-group">
                <label for="regConfirmPassword">Konfirmasi Password</label>
                <input type="password" id="regConfirmPassword" name="password_confirmation" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="login-btn">
                <span class="btn-text">Daftar</span>
            </button>
            <div class="error-message @if($errors->any()) show @endif">
                @if($errors->any())
                    {{ $errors->first() }}
                @endif
            </div>
        </form>
        <div class="login-footer">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
        </div>
    </div>
@endsection
