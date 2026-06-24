# 📱 Mobile Integration Guide - WebDokter API

## 🎯 Panduan Lengkap Integrasi API dengan Aplikasi Mobile

Dokumentasi ini menjelaskan cara menghubungkan aplikasi mobile Anda (Flutter, React Native, atau framework lain) dengan API WebDokter.

---

## 🚀 Quick Start (5 Menit)

### 1. Verifikasi API Status
Buka browser dan akses:
```
http://localhost/WebDokter/api
```

Anda akan melihat list semua endpoint yang tersedia.

### 2. Test Endpoint (Browser)
Akses file tester:
```
http://localhost/WebDokter/api/examples/api_tester.html
```

Klik tombol untuk test setiap endpoint.

### 3. Setup di Mobile App

#### Flutter
```dart
import 'package:http/http.dart' as http;

// Copy isi dari flutter_client.dart
// Import dan gunakan WebDokterApiClient

final organs = await WebDokterApiClient.getOrgans();
```

#### React Native
```javascript
import WebDokterAPI from './api/WebDokterAPI';

const api = new WebDokterAPI();
const organs = await api.getOrgans();
```

#### Vanilla JS / Web
```html
<script src="vanilla_js_client.js"></script>
<script>
  const api = new WebDokterAPI();
  api.getOrgans().then(organs => console.log(organs));
</script>
```

---

## 📚 Detailed API Reference

### Base URL Configuration

**Development:**
```
http://localhost/WebDokter/api
```

**Production:**
```
https://yourdomain.com/api
```

### Authentication

Saat ini API tidak memerlukan authentication. Jika ingin menambahkan:

```php
// Di db.php, tambahkan:
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    sendError('Unauthorized', 401);
}
```

### Request Format

Semua request adalah `GET`:
```
GET /api/organ.php
GET /api/penyakit.php?organId=1
GET /api/penyakit.php?id=5
```

### Response Format

Semua response mengikuti format JSON:
```json
{
  "success": true,
  "message": "Success message",
  "data": [
    { "id": 1, "nama": "..." }
  ]
}
```

---

## 🔗 Endpoint Details

### 1. GET /organ.php
**Daftar semua kategori organ**

**Response:**
```json
{
  "success": true,
  "message": "Daftar organ berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama": "Jantung",
      "deskripsi": "Organ pemompa darah",
      "icon": "heart",
      "warna": "#FF0000"
    }
  ]
}
```

---

### 2. GET /penyakit.php
**Daftar penyakit (dengan filter optional)**

**Tanpa parameter:**
```
GET /penyakit.php
```

**Dengan filter organ:**
```
GET /penyakit.php?organId=1
```

**Detail penyakit:**
```
GET /penyakit.php?id=5
```

**Response:**
```json
{
  "success": true,
  "message": "Detail penyakit berhasil diambil",
  "data": {
    "id": 5,
    "nama": "Hipertensi",
    "organ_nama": "Jantung",
    "deskripsi_singkat": "Tekanan darah tinggi",
    "penyebab_utama": "...",
    "gejala": "Sakit kepala, pusing",
    "bahaya": "Stroke, serangan jantung",
    "cara_mencegah": "Olahraga teratur",
    "cara_mengurangi": "Kurangi garam",
    "gambar": "path/to/image.jpg"
  }
}
```

---

### 3. GET /profil_dokter.php
**Profil dokter lengkap**

**Response:**
```json
{
  "success": true,
  "message": "Profil dokter berhasil diambil",
  "data": {
    "id": 1,
    "nama": "Dr. Nama Lengkap",
    "spesialisasi": "Dokter Umum",
    "gelar": "S.Ked, Sp.PD",
    "deskripsi": "Pengalaman 10 tahun",
    "foto": "photos/doctor.jpg",
    "total_pasien": 500,
    "total_sertifikat": 5,
    "total_penghargaan": 3,
    "telepon": "08xx-xxxx-xxxx",
    "email": "dokter@example.com",
    "riwayat_pendidikan": [
      {
        "id": 1,
        "jenis": "S1",
        "judul": "Kedokteran Umum",
        "institusi": "Universitas X",
        "periode": "2010-2014"
      }
    ],
    "sertifikat": [...],
    "keahlian_khusus": [...]
  }
}
```

---

### 4. GET /jadwal_praktek.php
**Jadwal praktik dokter**

**Semua jadwal:**
```
GET /jadwal_praktek.php
```

**Detail jadwal:**
```
GET /jadwal_praktek.php?id=1
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
      "lokasi": "Ruang Praktik 1"
    }
  ]
}
```

---

### 5. GET /pelayanan.php
**Daftar layanan medis**

**Response:**
```json
{
  "success": true,
  "message": "Daftar pelayanan berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama": "Pemeriksaan Umum",
      "deskripsi": "Konsultasi medis dasar",
      "icon": "stethoscope",
      "warna": "#0099FF",
      "link_eksternal": "https://..."
    }
  ]
}
```

---

## 💻 Client Code Examples

### Flutter Complete Example

```dart
import 'package:flutter/material.dart';
import 'api/flutter_client.dart';

void main() => runApp(MyApp());

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'WebDokter',
      home: OrgansPage(),
    );
  }
}

class OrgansPage extends StatefulWidget {
  @override
  _OrgansPageState createState() => _OrgansPageState();
}

class _OrgansPageState extends State<OrgansPage> {
  late Future<List<Map<String, dynamic>>> organs;

  @override
  void initState() {
    super.initState();
    organs = WebDokterApiClient.getOrgans();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Organ Tubuh')),
      body: FutureBuilder(
        future: organs,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          }
          
          return ListView.builder(
            itemCount: snapshot.data!.length,
            itemBuilder: (context, index) {
              final organ = snapshot.data![index];
              return Card(
                child: ListTile(
                  title: Text(organ['nama']),
                  subtitle: Text(organ['deskripsi']),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
```

### React Native Complete Example

```javascript
import React, { useState, useEffect } from 'react';
import {
  View,
  FlatList,
  Text,
  StyleSheet,
  ActivityIndicator,
} from 'react-native';
import WebDokterAPI from './api/WebDokterAPI';

export default function OrgansScreen() {
  const [organs, setOrgans] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    loadOrgans();
  }, []);

  const loadOrgans = async () => {
    try {
      const data = await WebDokterAPI.getOrgans();
      setOrgans(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <ActivityIndicator size="large" />;
  if (error) return <Text>Error: {error}</Text>;

  return (
    <FlatList
      data={organs}
      keyExtractor={(item) => item.id.toString()}
      renderItem={({ item }) => (
        <View style={styles.item}>
          <Text style={styles.title}>{item.nama}</Text>
          <Text style={styles.description}>{item.deskripsi}</Text>
        </View>
      )}
    />
  );
}

const styles = StyleSheet.create({
  item: {
    padding: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  title: {
    fontSize: 16,
    fontWeight: 'bold',
  },
  description: {
    fontSize: 14,
    color: '#666',
    marginTop: 5,
  },
});
```

---

## ⚙️ Configuration

### Environment Setup

Edit di `api/config.php`:
```php
define('ENVIRONMENT', 'development'); // atau 'production'
```

### Database Connection

Edit di `config/Koneksi.php`:
```php
private $host = 'localhost';
private $db_name = 'webdokter';
private $user = 'root';
private $password = ''; // ubah sesuai password Anda
```

---

## 🔒 Security Checklist

- [ ] Gunakan HTTPS di production
- [ ] Implementasikan API authentication jika diperlukan
- [ ] Validasi semua input dari client
- [ ] Set proper CORS headers
- [ ] Implementasikan rate limiting
- [ ] Log semua API access
- [ ] Jangan expose credentials di client

---

## 🐛 Troubleshooting

### CORS Error
**Masalah:** `Access-Control-Allow-Origin` error

**Solusi:** Pastikan `db.php` memiliki header CORS:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
```

### Database Connection Error
**Masalah:** `Tidak dapat terhubung ke database`

**Solusi:** Periksa `config/Koneksi.php`:
```php
// Pastikan credentials benar
$host = 'localhost';
$db_name = 'webdokter';
$user = 'root';
$password = ''; // sesuaikan
```

### Empty Response
**Masalah:** API mengembalikan data kosong

**Solusi:** Periksa:
1. Database sudah diisi data
2. Field status = 'aktif' di database
3. Query SQL di endpoint

### Timeout Error
**Masalah:** Request timeout

**Solusi:** Naikkan timeout di client:
```dart
// Flutter
const Duration timeout = Duration(seconds: 60);

// JavaScript
const timeout = 60000; // 60 detik
```

---

## 📞 Support & Help

1. **Check API Documentation:**
   - Baca [README.md](./README.md)
   - Akses API Tester: `http://localhost/WebDokter/api/examples/api_tester.html`

2. **Debug dengan Network Tools:**
   - Browser DevTools (F12 → Network tab)
   - Postman atau Insomnia
   - Charles atau Fiddler

3. **Common Issues:**
   - Pastikan XAMPP running
   - Pastikan file API di path yang benar
   - Periksa permissions folder

---

## 📝 API Change Log

### Version 1.0
- Initial API release
- 5 endpoints untuk mobile integration
- CORS enabled
- Standard JSON response format

---

**Terakhir diupdate:** 2024
