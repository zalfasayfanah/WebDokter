<?php
require_once __DIR__ . '/db.php';

try {
    $db = getConnection();
    $query = "SELECT id, nama, deskripsi, gambar AS icon, gambar, warna FROM kategori_organ_home WHERE status = 'aktif' ORDER BY nama";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse($rows, 'Daftar organ berhasil diambil');
} catch (PDOException $e) {
    sendError('Kesalahan database: ' . $e->getMessage(), 500);
}
