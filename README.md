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

1. **Clone Repository**
   ```bash
   git clone https://github.com/riskrrtt/GIS-Fasilitas-Umum-Tahunan.git
   cd GIS-Fasilitas-Umum-Tahunan
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi database di file `.env`:
   ```env
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Key & Migrasi Database**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses aplikasi di `http://localhost:8000`.

## Lisensi

[MIT license](https://opensource.org/licenses/MIT).
