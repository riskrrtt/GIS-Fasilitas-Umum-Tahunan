# GIS Fasilitas Umum Tahunan

Sistem Informasi Geografis (SIG) berbasis web untuk pemetaan fasilitas umum dan potensi di Desa Tahunan. Sistem ini memvisualisasikan lokasi-lokasi penting seperti sekolah, pasar, dan tempat ibadah menggunakan peta interaktif.

## Fitur Utama

- **Peta Interaktif**: Visualisasi lokasi menggunakan LeafletJS dan OpenStreetMap.
- **Manajemen Data (CRUD)**:
  - Kelola data Sekolah (Negeri/Swasta, Jenjang).
  - Kelola data Pasar.
  - Kelola data Tempat Ibadah.
  - Kelola data Desa dan batas wilayah (Polygon/Polyline).
- **Dashboard Statistik**: Ringkasan jumlah fasilitas yang terdata.
- **Informasi Detail**: Modal popup untuk menampilkan detail setiap lokasi.

## Teknologi yang Digunakan

- **Backend**: Laravel 8 (PHP 8.2+)
- **Database**: MySQL
- **Frontend**: 
  - Bootstrap (UI Framework)
  - jQuery
  - Blade Templates
- **Peta**: 
  - [LeafletJS](https://leafletjs.com/)
  - [OpenStreetMap](https://www.openstreetmap.org/)
  - [Leaflet-Geoman](https://geoman.io/) (untuk menggambar/edit peta)

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL Database
- NodeJS & NPM (opsional, untuk compile asset jika ada perubahan CSS/JS)

## Cara Instalasi

## Cara Instalasi (Otomatis & Mudah)

**Untuk Windows:**

1. **Clone Repository** (atau download zip dan extract)
   ```bash
   git clone https://github.com/riskrrtt/GIS-Fasilitas-Umum-Tahunan.git
   ```

2. **Siapkan Database**
   - Buat database kosong di phpMyAdmin (misal: `gis_tahunan`).

3. **Jalankan Installer**
   - Klik dua kali file **`install.bat`** di dalam folder proyek.
   - Ikuti petunjuk di layar.

---

## Cara Instalasi Manual (Alternatif)

1. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

2. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`, lalu setup database.

3. **Generate Key & Migrasi**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

4. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

## Lisensi

[MIT license](https://opensource.org/licenses/MIT).
