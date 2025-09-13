# Sistem Informasi Penyakit Dalam

Sistem informasi penyakit dalam yang memungkinkan pengguna untuk mengeksplorasi berbagai kategori penyakit berdasarkan organ dan karakteristiknya.

## Fitur Utama

1. **Halaman Kategori Penyakit** (`penyakit.php`)
   - Menampilkan semua kategori organ
   - Navigasi sidebar untuk berpindah antar kategori
   - Pencarian penyakit dan kategori
   - Tampilan yang responsif

2. **Halaman Daftar Penyakit** (`penyakit_kategori.php`)
   - Menampilkan daftar penyakit berdasarkan kategori organ
   - Navigasi sidebar yang aktif sesuai kategori
   - Tombol penyakit yang dapat diklik

3. **Halaman Detail Penyakit** (`penyakit_detail.php`)
   - Informasi lengkap tentang penyakit
   - Penyebab utama, gejala, bahaya, dan cara pencegahan
   - Tombol kembali ke daftar penyakit
   - Navigasi sidebar yang konsisten

## Struktur Database

### Tabel `kategori_organ`
- `id`: Primary key
- `nama`: Nama kategori organ
- `deskripsi`: Deskripsi kategori
- `urutan`: Urutan tampilan
- `status`: Status aktif/nonaktif

### Tabel `penyakit`
- `id`: Primary key
- `kategori_id`: Foreign key ke kategori_organ
- `nama`: Nama penyakit
- `deskripsi_singkat`: Deskripsi singkat penyakit
- `penyebab_utama`: Penyebab utama penyakit
- `gejala`: Gejala yang timbul
- `bahaya`: Bahaya jika dibiarkan
- `cara_mencegah`: Cara pencegahan
- `cara_mengurangi`: Cara pengobatan
- `status`: Status aktif/nonaktif

## Instalasi

1. **Setup Database**
   ```sql
   -- Jalankan script database_rs.sql untuk membuat database dan tabel
   -- Kemudian jalankan insert_penyakit.sql untuk menambahkan data penyakit
   ```

2. **Konfigurasi Database**
   - Edit file `config/Koneksi.php` sesuai dengan konfigurasi database Anda
   - Pastikan database `medical_website` sudah dibuat

3. **Akses Halaman**
   - Buka `penyakit.php` untuk halaman utama kategori penyakit
   - Gunakan navigasi untuk berpindah antar halaman

## Kategori Organ yang Tersedia

1. **Mulut & Kerongkongan**
   - Stomatitis
   - Karies gigi (gigi berlubang)
   - Gingivitis (Radang Gusi)
   - Periodontitis
   - Kanker mulut

2. **Lambung**
   - GERD (Gastroesophageal Reflux Disease)
   - Gastritis Kronis
   - Tukak Lambung (Ulkus Peptikum)
   - Diare
   - Disentri
   - IBS (Irritable Bowel Syndrome)

3. **Usus Halus & Usus Besar**
   - (Data akan ditambahkan sesuai kebutuhan)

4. **Hati, Empedu & Pankreas**
   - (Data akan ditambahkan sesuai kebutuhan)

## Styling dan Responsivitas

- Menggunakan CSS modern dengan gradient dan shadow
- Sidebar navigasi yang tetap terlihat
- Responsive design untuk mobile dan desktop
- Konsisten dengan desain website utama

## Navigasi

- **Header**: Beranda, Jadwal Praktek, Penyakit, Pelayanan
- **Sidebar**: Navigasi antar kategori organ
- **Breadcrumb**: Tombol kembali untuk navigasi yang mudah

## Pengembangan Lebih Lanjut

1. **Tambah Data Penyakit**
   - Edit file `insert_penyakit.sql` untuk menambahkan penyakit baru
   - Jalankan script SQL untuk update database

2. **Tambah Kategori Organ**
   - Insert data baru ke tabel `kategori_organ`
   - Update icon di file PHP sesuai kebutuhan

3. **Fitur Pencarian**
   - Implementasi pencarian real-time
   - Filter berdasarkan kategori dan gejala

4. **Admin Panel**
   - Interface untuk mengelola data penyakit
   - CRUD operations untuk penyakit dan kategori