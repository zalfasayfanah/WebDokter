<?php
require_once __DIR__ . '/db.php';

try {
    $db = getConnection();

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $query = "SELECT p.id, p.kategori_id, k.nama AS organ_nama, p.nama, p.deskripsi_singkat, p.penyebab_utama, p.gejala, p.bahaya, p.cara_mencegah, p.cara_mengurangi, p.gambar, p.status FROM penyakit p LEFT JOIN kategori_organ_home k ON p.kategori_id = k.id WHERE p.id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $_GET['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            sendError('Data penyakit tidak ditemukan', 404);
        }

        sendResponse($result, 'Detail penyakit berhasil diambil');
    }

    $conditions = [];
    $params = [];

    if (isset($_GET['organId']) && is_numeric($_GET['organId'])) {
        $conditions[] = 'p.kategori_id = :organId';
        $params[':organId'] = $_GET['organId'];
    }

    $conditions[] = "p.status = 'aktif'";
    $where = 'WHERE ' . implode(' AND ', $conditions);

    $query = "SELECT p.id, p.kategori_id, k.nama AS organ_nama, p.nama, p.deskripsi_singkat, p.gambar, p.status FROM penyakit p LEFT JOIN kategori_organ_home k ON p.kategori_id = k.id $where ORDER BY p.nama";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse($rows, 'Daftar penyakit berhasil diambil');
} catch (PDOException $e) {
    sendError('Kesalahan database: ' . $e->getMessage(), 500);
}
