@extends('layouts.dashboard')

@section('title', 'Cek Jadwal & Reservasi | Sistem Pendaftaran')

@section('content')
<div class="welcome-section mb-4">
    <h1>Reservasi Fasilitas</h1>
    <p>Cek ketersediaan jadwal dan lengkapi formulir peminjaman untuk <strong>{{ $facility->name }}</strong></p>
</div>

@if($errors->any())
<div class="alert" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); padding: 1rem; border-radius: 8px; color: #fca5a5; margin-bottom: 1.5rem;">
    <ul style="margin-left: 1.5rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="content-grid" style="grid-template-columns: 1fr;">
    
    <!-- Bagian Cek Jadwal -->
    <div class="glass-panel mb-4">
        <div class="section-header">
            <h2><i class="fas fa-calendar-alt"></i> Jadwal Terisi (Mendatang)</h2>
        </div>
        
        @if($reservations->isEmpty())
            <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">Belum ada jadwal yang terisi. Fasilitas ini kosong!</p>
        @else
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 1rem; color: var(--text-muted);">Nama Kegiatan</th>
                            <th style="padding: 1rem; color: var(--text-muted);">Mulai</th>
                            <th style="padding: 1rem; color: var(--text-muted);">Selesai</th>
                            <th style="padding: 1rem; color: var(--text-muted);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $res)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 1rem;">
                                @if(Auth::user()->role === 'admin' || Auth::id() === $res->user_id)
                                    {{ $res->activity_name ?? 'Kegiatan Tidak Disebutkan' }}
                                @else
                                    <span style="color: var(--text-muted); font-style: italic;">Informasi Diprivasi</span>
                                @endif
                            </td>
                            <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($res->start_time)->format('d M Y, H:i') }}</td>
                            <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($res->end_time)->format('d M Y, H:i') }}</td>
                            <td style="padding: 1rem;">
                                @if($res->status == 'approved')
                                    <span class="status status-approved">Disetujui</span>
                                @else
                                    <span class="status status-pending">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Bagian Formulir Peminjaman -->
    <div class="glass-panel">
        <div class="section-header">
            <h2><i class="fas fa-edit"></i> Formulir Peminjaman Fasilitas</h2>
        </div>
        
        <form action="{{ route('reservations.store') }}" method="POST" class="facility-form" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
            
            <div style="background: rgba(15,23,42,0.5); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: var(--primary-color);">1. Identitas Pemohon</h3>
                
                <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem;">
                    <label style="font-weight: 500;">Nama Lengkap</label>
                    <input type="text" value="{{ Auth::user()->name }}" disabled style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-muted); width: 100%;">
                    <small style="color: var(--text-muted);">Nama diambil dari akun Anda.</small>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="nim" style="font-weight: 500;">NIM <span style="color: red;">*</span></label>
                        <input type="text" name="nim" id="nim" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="department" style="font-weight: 500;">Program Studi / Departemen <span style="color: red;">*</span></label>
                        <input type="text" name="department" id="department" placeholder="Contoh: S1 Informatika" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="organization" style="font-weight: 500;">Nama Organisasi / UKM (Opsional)</label>
                        <input type="text" name="organization" id="organization" placeholder="Jika ada" style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="phone" style="font-weight: 500;">Nomor HP / WhatsApp <span style="color: red;">*</span></label>
                        <input type="text" name="phone" id="phone" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                </div>
            </div>

            <div style="background: rgba(15,23,42,0.5); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: var(--primary-color);">2. Rincian Waktu Penggunaan</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Fasilitas terpilih: <strong>{{ $facility->name }}</strong></p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="start_time" style="font-weight: 500;">Waktu Mulai <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="start_time" id="start_time" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="end_time" style="font-weight: 500;">Waktu Selesai <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="end_time" id="end_time" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                </div>
            </div>
            
            <div style="background: rgba(15,23,42,0.5); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: var(--primary-color);">3. Detail Kegiatan</h3>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="activity_name" style="font-weight: 500;">Nama / Keperluan Kegiatan <span style="color: red;">*</span></label>
                        <input type="text" name="activity_name" id="activity_name" placeholder="Contoh: Rapat Kerja HMJ" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="participant_count" style="font-weight: 500;">Estimasi Peserta <span style="color: red;">*</span></label>
                        <input type="number" name="participant_count" id="participant_count" min="1" placeholder="Jumlah orang" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-main); width: 100%;">
                    </div>
                </div>
            </div>
            
            <div style="background: rgba(15,23,42,0.5); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: var(--primary-color);">4. Pernyataan Konfirmasi</h3>
                
                <label style="display: flex; align-items: flex-start; gap: 1rem; cursor: pointer;">
                    <input type="checkbox" name="commitment_agreed" value="1" required style="margin-top: 0.3rem; transform: scale(1.2);">
                    <span style="line-height: 1.5; color: #cbd5e1;">Saya selaku pemohon berkomitmen untuk menjaga kebersihan, kerapian, tidak merusak fasilitas, dan mengembalikan ruangan ke kondisi semula. Saya bersedia menanggung akibat apabila terjadi kerusakan.</span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                <a href="{{ route('dashboard') }}" style="padding: 0.8rem 1.5rem; border-radius: 8px; color: white; text-decoration: none; border: 1px solid var(--border-color);">Batal</a>
                <button type="submit" class="btn-reserve" style="width: auto; padding: 0.8rem 2rem;">Kirim Permohonan Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endsection
