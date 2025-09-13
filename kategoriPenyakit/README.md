# Sistem Informasi Penyakit Dalam

Folder ini berisi sistem informasi penyakit dalam yang memungkinkan pengguna untuk mengeksplorasi berbagai kategori penyakit berdasarkan organ dan karakteristiknya.

## File yang Ada

- `penyakit.php` - Halaman utama kategori penyakit
- `penyakit_kategori.php` - Halaman daftar penyakit per kategori organ
- `penyakit_detail.php` - Halaman detail penyakit
- `insert_penyakit.sql` - Script SQL untuk menambahkan data penyakit
- `includes/header.php` - Header khusus untuk folder ini
- `includes/footer.php` - Footer khusus untuk folder ini

## Cara Menggunakan

1. **Akses dari halaman utama:**
   - Klik menu "Penyakit Dalam" di navbar utama
   - Atau akses langsung: `kategoriPenyakit/penyakit.php`

2. **Navigasi:**
   - Gunakan sidebar untuk berpindah antar kategori organ
   - Klik tombol kategori di halaman utama
   - Pilih penyakit untuk melihat detail lengkap

## Setup Database

Jalankan script SQL berikut untuk menambahkan data penyakit:

```sql
-- Jalankan insert_penyakit.sql untuk menambahkan data penyakit
```

## Struktur Folder

```
kategoriPenyakit/
├── penyakit.php              # Halaman utama kategori
├── penyakit_kategori.php      # Daftar penyakit per kategori
├── penyakit_detail.php       # Detail penyakit
├── insert_penyakit.sql       # Data penyakit
├── includes/
│   ├── header.php           # Header khusus
│   └── footer.php           # Footer khusus
└── README.md                # Dokumentasi ini
```

## Kategori Organ

1. **Mulut & Kerongkongan** - Stomatitis, Karies gigi, Gingivitis, dll.
2. **Lambung** - GERD, Gastritis, Tukak Lambung, Diare, dll.
3. **Usus Halus & Usus Besar** - (Data akan ditambahkan)
4. **Hati, Empedu & Pankreas** - (Data akan ditambahkan)

## Pengembangan

Untuk menambahkan penyakit baru, edit file `insert_penyakit.sql` dan jalankan script SQL tersebut.