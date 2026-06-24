# API WebDokter - Mobile Integration Guide

REST API untuk menghubungkan aplikasi mobile (Flutter, React Native, etc) dengan WebDokter.

## 🚀 Quick Start

### URL Dasar

**Development (Local XAMPP):**
```
http://localhost/WebDokter/api
```

**Production:**
```
https://yourdomain.com/api
```

## 📋 Endpoints Documentation

### 1. Jadwal Praktik

#### Get All Schedules
```
GET /api/jadwal_praktek.php
```
**Response:**
```json
{
  "success": true,
  "message": "Jadwal praktik berhasil diambil",
  "data": [
    {
      "id": 1,
      "hari": "Senin",
      "jam_mulai": "08:00",
      "jam_selesai": "12:00",
      "lokasi": "Ruang 1"
    }
  ]
}
```

#### Get Schedule Detail
```
GET /api/jadwal_praktek.php?id={id}
```

---

### 2. Profil Dokter

```
GET /api/profil_dokter.php
```
**Response:**
```json
{
  "success": true,
  "message": "Profil dokter berhasil diambil",
  "data": {
    "id": 1,
    "nama": "Dr. Nama",
    "spesialisasi": "Dokter Umum",
    "gelar": "S.Ked, Sp.PD",
    "deskripsi": "...",
    "foto": "path/to/photo.jpg",
    "telepon": "08xx-xxxx-xxxx",
    "email": "email@example.com",
    "riwayat_pendidikan": [...],
    "sertifikat": [...],
    "keahlian_khusus": [...]
  }
}
```

---

### 3. Kategori Organ

```
GET /api/organ.php
```
**Response:**
```json
{
  "success": true,
  "message": "Daftar organ berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama": "Jantung",
      "deskripsi": "...",
      "icon": "icon-name",
      "warna": "#FF0000"
    }
  ]
}
```

---

### 4. Penyakit

#### Get All Diseases
```
GET /api/penyakit.php
```

#### Get Diseases by Organ
```
GET /api/penyakit.php?organId={id}
```

#### Get Disease Detail
```
GET /api/penyakit.php?id={id}
```

#### Get Disease Detail from penyakit table
```
GET /api/detail_penyakit.php?id={id}
```

**Response:**
```json
{
  "success": true,
  "message": "Detail penyakit berhasil diambil",
  "data": {
    "id": 1,
    "kategori_id": 2,
    "kategori_home_id": 2,
    "kategori_nama": "Jantung",
    "kategori_deskripsi": "Deskripsi kategori organ",
    "kategori_gambar": "path/to/category-image.jpg",
    "kategori_warna": "#3b82f6",
    "nama": "Penyakit Jantung",
    "deskripsi_singkat": "...",
    "penyebab_utama": "...",
    "gejala": "...",
    "bahaya": "...",
    "cara_mencegah": "...",
    "cara_mengurangi": "...",
    "gambar": "path/to/image.jpg",
    "status": "aktif"
  }
}
```

---

### 5. Layanan Medis

```
GET /api/pelayanan.php
```
**Response:**
```json
{
  "success": true,
  "message": "Daftar pelayanan berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama": "Pemeriksaan Umum",
      "deskripsi": "...",
      "icon": "icon-name",
      "warna": "#0099FF",
      "link_eksternal": "https://..."
    }
  ]
}
```

---

## 🔧 Setup untuk Mobile App

### Flutter Example
```dart
const String baseUrl = 'http://localhost/WebDokter/api';

Future<List<Organ>> getOrgans() async {
  final response = await http.get(
    Uri.parse('$baseUrl/organ.php'),
  );
  
  if (response.statusCode == 200) {
    final json = jsonDecode(response.body);
    return (json['data'] as List)
      .map((item) => Organ.fromJson(item))
      .toList();
  }
  throw Exception('Failed to load organs');
}
```

### React Native Example
```javascript
const API_BASE = 'http://localhost/WebDokter/api';

const getOrgans = async () => {
  try {
    const response = await fetch(`${API_BASE}/organ.php`);
    const data = await response.json();
    if (data.success) {
      return data.data;
    }
  } catch (error) {
    console.error('Error:', error);
  }
};
```

---

## ✅ Response Format

Semua endpoint mengikuti format standar:

```json
{
  "success": true|false,
  "message": "Pesan status atau error",
  "data": null|object|array
}
```

---

## ⚙️ Configuration

### Database Connection
Update `config/Koneksi.php`:
```php
private $host = 'localhost';
private $db_name = 'webdokter';
private $user = 'root';
private $password = ''; // ubah jika ada password
```

### CORS Headers
Semua endpoint sudah dikonfigurasi dengan CORS untuk mobile:
- Allowed Origin: `*` (terbuka untuk semua domain)
- Allowed Methods: `GET, OPTIONS, POST, PUT, DELETE`
- Allowed Headers: `Content-Type, Authorization`

---

## 🔒 Security Notes

1. **Jangan expose database credentials** di client
2. **Gunakan HTTPS** untuk production
3. **Tambahkan API authentication** jika diperlukan:
   ```php
   if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
       sendError('Unauthorized', 401);
   }
   ```
4. **Implementasikan rate limiting** untuk mencegah abuse
5. **Validate & sanitize** semua input dari client

---

## 🐛 Error Handling

API mengembalikan error dengan format:
```json
{
  "success": false,
  "message": "Error description",
  "data": null
}
```

**Status Codes:**
- `200` - OK
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `500` - Server Error

---

## 📞 Support

Jika ada pertanyaan atau issue, periksa:
1. Database connection di `config/Koneksi.php`
2. Table names dan column names sesuai dengan query
3. File permissions
4. Browser console untuk CORS errors
