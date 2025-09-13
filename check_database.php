<?php
require_once 'config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo "Koneksi database gagal!\n";
    exit;
}

echo "Koneksi database berhasil!\n";

// Cek tabel kategori_organ
try {
    $query = "SELECT COUNT(*) as count FROM kategori_organ";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Jumlah kategori organ: " . $result['count'] . "\n";
} catch (PDOException $e) {
    echo "Error kategori_organ: " . $e->getMessage() . "\n";
}

// Cek tabel penyakit
try {
    $query = "SELECT COUNT(*) as count FROM penyakit";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Jumlah penyakit: " . $result['count'] . "\n";
} catch (PDOException $e) {
    echo "Error penyakit: " . $e->getMessage() . "\n";
}

// Cek tabel dokter
try {
    $query = "SELECT COUNT(*) as count FROM dokter";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Jumlah dokter: " . $result['count'] . "\n";
} catch (PDOException $e) {
    echo "Error dokter: " . $e->getMessage() . "\n";
}

echo "Pengecekan selesai!\n";
?>