<?php
require_once __DIR__ . '/db.php';

try {
    $db = getConnection();
    $query = "SELECT id, nama, deskripsi, icon, warna, link_eksternal FROM layanan_medis WHERE status = 'aktif' ORDER BY urutan, nama";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse($rows, 'Daftar pelayanan berhasil diambil');
} catch (PDOException $e) {
    sendError('Kesalahan database: ' . $e->getMessage(), 500);
}
