<?php
require_once __DIR__ . '/db.php';

try {
    $db = getConnection();

    $query = "SELECT id, nama, spesialisasi, gelar, deskripsi, foto, total_pasien, total_sertifikat, total_penghargaan, telepon, email FROM dokter ORDER BY id ASC LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $dokter = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dokter) {
        sendError('Data dokter tidak ditemukan', 404);
    }

    $dokterId = $dokter['id'];

    $stmt = $db->prepare("SELECT id, jenis, judul, institusi, periode, deskripsi, urutan FROM riwayat_pendidikan WHERE dokter_id = :dokter_id ORDER BY urutan, id");
    $stmt->execute([':dokter_id' => $dokterId]);
    $dokter['riwayat_pendidikan'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT id, nama_sertifikat, institusi, tahun, deskripsi FROM sertifikat WHERE dokter_id = :dokter_id ORDER BY id");
    $stmt->execute([':dokter_id' => $dokterId]);
    $dokter['sertifikat'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT id, nama_keahlian AS nama, deskripsi, icon, warna FROM keahlian_khusus WHERE dokter_id = :dokter_id ORDER BY id");
    $stmt->execute([':dokter_id' => $dokterId]);
    $dokter['keahlian_khusus'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse($dokter, 'Profil dokter berhasil diambil');
} catch (PDOException $e) {
    sendError('Kesalahan database: ' . $e->getMessage(), 500);
}
