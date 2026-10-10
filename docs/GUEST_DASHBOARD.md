# Dashboard Tamu (Guest)

Dokumentasi fitur **Dashboard Tamu** pada CampusRes: halaman publik *read-only* yang bisa dibuka **tanpa login** untuk:

1. Melihat semua reservasi yang telah dibuat (hanya yang **disetujui admin**).
2. Melihat semua fasilitas kampus.

---

## 1. Ringkasan Keputusan Desain

| Aspek | Keputusan | Alasan |
|---|---|---|
| Jenis tamu | Pengunjung tanpa akun (tidak login) | Tidak perlu role baru, tidak ada perubahan database |
| Akses | Publik, tanpa middleware `auth`/`guest` | Bisa dibuka siapa saja, termasuk pengguna yang sudah login |
| Status reservasi yang tampil | Hanya `approved` | Tamu cukup tahu kapan fasilitas terpakai; reservasi `pending`/`rejected`/`cancelled` bersifat internal |
| Data yang tampil | Fasilitas, lokasi, waktu, nama kegiatan, penyelenggara (organisasi), jumlah peserta | Informasi jadwal saja |
| Data yang **disembunyikan** | Nama pemesan, email, NIM, prodi, no. WA, alasan pembatalan | Data pribadi tidak boleh terlihat publik |
| Hak aksi | Tidak ada (tanpa tombol buat/ubah/hapus/batal) | *Read-only* |

---

## 2. Daftar File

### File BARU

| File | Fungsi |
|---|---|
| `app/Http/Controllers/GuestController.php` | Controller yang mengambil data untuk 3 halaman tamu |
| `resources/views/layouts/guest.blade.php` | Layout (sidebar + header) khusus tamu |
| `resources/views/guest/dashboard.blade.php` | Halaman utama dashboard tamu |
| `resources/views/guest/reservations.blade.php` | Halaman daftar jadwal reservasi |
| `resources/views/guest/facilities.blade.php` | Halaman daftar fasilitas |
| `tests/Feature/GuestDashboardTest.php` | Feature test untuk fitur tamu |
| `docs/GUEST_DASHBOARD.md` | Dokumen ini |

### File yang DIUBAH

| File | Perubahan |
|---|---|
| `routes/web.php` | Menambah `use App\Http\Controllers\GuestController;` dan grup route `/guest` (3 route) |
| `resources/views/auth/login.blade.php` | Menambah tautan **"Masuk sebagai Tamu"** di bagian footer form login |

### File yang TIDAK diubah (hanya dipakai ulang)

| File | Dipakai untuk |
|---|---|
| `app/Models/Facility.php` | Query data fasilitas |
| `app/Models/Reservation.php` | Query data reservasi + relasi `facility()` |
| `resources/views/layouts/app.blade.php` | Kerangka HTML dasar (font Inter, Font Awesome) — di-*extend* oleh `layouts/guest` |
| `public/dashboard.css` | Seluruh styling (sidebar, kartu, status badge, light/dark mode) |
| `public/dashboard.js` | Animasi kartu + tombol ganti tema (light/dark) |

**Tidak ada migrasi, tidak ada perubahan database, tidak ada perubahan model, dan tidak ada dependency baru.**

---

## 3. Routing

Ditambahkan di `routes/web.php`, **di luar** grup middleware `guest` dan `auth`:

```php
// Dashboard Tamu (publik, read-only, tanpa login)
Route::prefix('guest')->name('guest.')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('dashboard');
    Route::get('/reservations', [GuestController::class, 'reservations'])->name('reservations');
    Route::get('/facilities', [GuestController::class, 'facilities'])->name('facilities');
});
```

| Method | URL | Nama Route | Controller | View |
|---|---|---|---|---|
| GET | `/guest` | `guest.dashboard` | `GuestController@index` | `guest.dashboard` |
| GET | `/guest/reservations` | `guest.reservations` | `GuestController@reservations` | `guest.reservations` |
| GET | `/guest/facilities` | `guest.facilities` | `GuestController@facilities` | `guest.facilities` |

> **Catatan:** Middleware `guest` milik Laravel (yang membungkus `/login`, `/register`) **berbeda** dengan fitur "Tamu" ini. Middleware `guest` Laravel akan mengalihkan pengguna yang sudah login, sehingga sengaja **tidak** dipakai di sini.

Query string yang didukung:

| Halaman | Parameter | Contoh |
|---|---|---|
| `/guest/reservations` | `date` (YYYY-MM-DD), `facility` (id fasilitas) | `/guest/reservations?date=2026-10-15&facility=3` |
| `/guest/facilities` | `search` (nama / lokasi / tipe) | `/guest/facilities?search=Lab` |

---

## 4. Controller — `GuestController`

### 4.1 Perlindungan data pribadi

Inti keamanan fitur ini ada pada konstanta dan helper berikut:

```php
private const PUBLIC_RESERVATION_COLUMNS = [
    'id', 'facility_id', 'start_time', 'end_time', 'status',
    'activity_name', 'organization', 'participant_count',
];

private function approvedReservations()
{
    return Reservation::select(self::PUBLIC_RESERVATION_COLUMNS)
        ->with('facility:id,name,location')
        ->where('status', 'approved');
}
```

- `select(...)` → kolom `nim`, `phone`, `department`, `user_id`, `cancellation_reason` **tidak pernah diambil** dari database, jadi tidak mungkin bocor ke view walau view-nya keliru.
- Relasi `user` **tidak** di-load, jadi nama/email pemesan tidak tersedia.
- `where('status', 'approved')` → hanya reservasi yang sudah disetujui.

> Jika suatu saat ingin menampilkan kolom tambahan ke publik, cukup tambahkan ke `PUBLIC_RESERVATION_COLUMNS`. **Jangan** menambahkan kolom data pribadi.

### 4.2 Method

**`index()`** → `/guest`

| Variabel ke view | Isi |
|---|---|
| `$facilities` | Semua fasilitas (2 pertama ditampilkan sebagai "Sorotan") |
| `$upcomingReservations` | 5 reservasi disetujui terdekat yang belum selesai (`end_time >= now()`), urut waktu mulai |
| `$facilityCount` | Jumlah fasilitas |
| `$approvedCount` | Jumlah seluruh reservasi `approved` |
| `$todayCount` | Jumlah reservasi `approved` yang dimulai hari ini |

**`reservations(Request $request)`** → `/guest/reservations`

- Validasi: `date` → `nullable|date`, `facility` → `nullable|integer|exists:facilities,id`.
- Semua reservasi `approved`, urut terbaru (`start_time` menurun), dengan filter opsional per tanggal dan per fasilitas.
- Mengirim `$reservations` dan `$facilities` (hanya `id`, `name`, untuk dropdown filter).

**`facilities(Request $request)`** → `/guest/facilities`

- Semua fasilitas, dengan pencarian opsional `search` pada kolom `name`, `location`, `type` (`LIKE %...%`).

---

## 5. View

### 5.1 `layouts/guest.blade.php`

Diturunkan dari struktur `layouts/dashboard.blade.php` milik pengguna login, dengan perbedaan:

| Bagian | `layouts/dashboard` (login) | `layouts/guest` (tamu) |
|---|---|---|
| Menu sidebar | Bergantung role (admin/user) | Dashboard, Jadwal Reservasi, Fasilitas |
| Footer sidebar | Tombol **Keluar** (POST logout) | Tidak ada |
| Search bar | Ke `facilities.index` | Ke `guest.facilities` |
| Lonceng notifikasi | Ada (query per user) | Dihapus |
| Kanan atas header | Avatar + nama user | Tombol **Masuk / Daftar** ke `/login` (latar `var(--primary-hover)`, teks putih — kontras ≥ 6:1 di mode gelap maupun terang) |
| Tombol back global | Ada | Dihapus (navigasi cukup lewat sidebar) |
| Section konten | `@yield('dashboard_content')` | `@yield('guest_content')` |
| `Auth::user()` | Dipakai | **Tidak dipakai sama sekali** (tidak ada user) |

CSS (`dashboard.css`) dan JS (`dashboard.js`) yang sama tetap dipakai, sehingga tampilan, animasi, serta toggle tema terang/gelap konsisten dengan dashboard yang lain.

### 5.2 `guest/dashboard.blade.php`

- Sambutan + tautan "Masuk" untuk membuat reservasi.
- 3 kartu statistik: **Fasilitas Tersedia**, **Reservasi Disetujui**, **Terpakai Hari Ini**.
- Panel **Sorotan Fasilitas** (2 fasilitas) → "Lihat Semua" ke `/guest/facilities`.
- Panel **Jadwal Terdekat** (5 reservasi) → "Lihat Semua" ke `/guest/reservations`.

### 5.3 `guest/reservations.blade.php`

- Filter dropdown fasilitas + tanggal, tombol **Filter** dan **Reset**.
- Setiap item: nama fasilitas, rentang waktu, lokasi, badge status, dan (jika ada) kegiatan, penyelenggara, jumlah peserta.
- Badge: **Disetujui** (hijau) jika belum selesai, **Selesai** (biru) jika `end_time` sudah lewat.

### 5.4 `guest/facilities.blade.php`

- Grid kartu semua fasilitas: gambar, nama, lokasi, tipe, kapasitas, deskripsi, label **Perbaikan** jika `status = maintenance`.
- Tombol **Lihat Jadwal** → `/guest/reservations?facility={id}` (langsung terfilter per fasilitas).
- Tombol **Masuk untuk Reservasi** → `/login`.

---

## 6. Alur Hubungan Antar Komponen

```
Halaman Login (/login)
   └── tautan "Masuk sebagai Tamu"
          │
          ▼
   GET /guest ──► GuestController@index ──► views/guest/dashboard
          │                                     │ extends
          │                                     ▼
          │                           layouts/guest ──► layouts/app
          │                                     │ memuat
          │                                     ▼
          │                      public/dashboard.css + dashboard.js
          │
   ├── GET /guest/reservations ──► GuestController@reservations
   │        └─ Reservation (approved, kolom publik) + relasi facility
   │
   └── GET /guest/facilities ──► GuestController@facilities
            └─ Facility (+ pencarian)
```

Model yang dipakai: `Reservation` (relasi `belongsTo Facility`) dan `Facility`. Relasi `Reservation → User` sengaja **tidak** dipakai.

---

## 7. Pengujian

File: `tests/Feature/GuestDashboardTest.php` (memakai `RefreshDatabase` dan SQLite in-memory dari `phpunit.xml`).

| Test | Yang diverifikasi |
|---|---|
| `test_guest_pages_are_accessible_without_login` | Ketiga halaman mengembalikan 200 tanpa login |
| `test_guest_sees_only_approved_reservations` | Reservasi `pending` tidak tampil |
| `test_guest_does_not_see_personal_data` | Nama pemesan, NIM, no. WA tidak muncul di dashboard maupun daftar reservasi |
| `test_guest_sees_all_facilities_and_can_search` | Semua fasilitas tampil & pencarian bekerja |
| `test_reservations_can_be_filtered_by_facility` | Filter fasilitas bekerja |
| `test_login_page_links_to_guest_dashboard` | Tautan "Masuk sebagai Tamu" ada di halaman login |

Menjalankan:

```bash
php artisan test --filter=GuestDashboardTest
```

> Catatan: `tests/Feature/ExampleTest.php` bawaan Laravel gagal sejak sebelum fitur ini dibuat, karena route `/` mengalihkan (302) ke `/login` sedangkan test tersebut mengharapkan 200. Test itu tidak terkait dengan fitur tamu.

---

## 8. Cara Mencoba

1. Pastikan Laragon berjalan, lalu buka `http://campusres.test/login`.
2. Klik **"Masuk sebagai Tamu"**, atau langsung buka `http://campusres.test/guest`.
3. Untuk melihat data di daftar jadwal, login sebagai admin (`admin` / `admin123`) dan setujui minimal satu reservasi — hanya reservasi berstatus disetujui yang muncul di halaman tamu.

---

## 9. Kemungkinan Pengembangan

- Tampilan kalender (mingguan/bulanan) untuk jadwal fasilitas.
- Pagination pada `/guest/reservations` jika jumlah reservasi sudah besar (saat ini memakai `->get()` tanpa batas).
- Membuat route `/` mengarah ke `/guest` alih-alih `/login` jika dashboard tamu ingin dijadikan halaman depan.
