<?php
require_once __DIR__ . '/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    sendError('Parameter id penyakit wajib dan harus numerik', 400);
}

try {
    $db = getConnection();
    $query = "SELECT p.id,
                     p.kategori_id,
                     p.nama,
                     p.deskripsi_singkat,
                     p.penyebab_utama,
                     p.gejala,
                     p.bahaya,
                     p.cara_mencegah,
                     p.cara_mengurangi,
                     p.gambar,
                     p.status,
                     kh.id AS kategori_home_id,
                     kh.nama AS kategori_nama,
                     kh.deskripsi AS kategori_deskripsi,
                     kh.gambar AS kategori_gambar,
                     kh.warna AS kategori_warna
              FROM penyakit p
              LEFT JOIN kategori_organ_home kh ON p.kategori_id = kh.id
              WHERE p.id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute([':id' => $_GET['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        sendError('Data penyakit tidak ditemukan', 404);
    }

    sendResponse($result, 'Detail penyakit berhasil diambil');
} catch (PDOException $e) {
    sendError('Kesalahan database: ' . $e->getMessage(), 500);
}
