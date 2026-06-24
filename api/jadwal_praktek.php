<?php
require_once __DIR__ . '/db.php';

try {
    $db = getConnection();

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $query = "
            SELECT
                tp.id AS tempat_id,
                tp.nama_tempat,
                tp.alamat,
                tp.telp,
                tp.gambar,
                wp.hari,
                wp.waktu
            FROM tempat_praktek tp
            LEFT JOIN waktu_praktek wp ON tp.id = wp.tempat_id
            WHERE tp.id = :id
            ORDER BY wp.hari, wp.waktu
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $_GET['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            sendError('Jadwal praktek tidak ditemukan', 404);
        }

        $result = [
            'id' => $rows[0]['tempat_id'],
            'nama' => $rows[0]['nama_tempat'],
            'alamat' => $rows[0]['alamat'],
            'imageUrl' => $rows[0]['gambar'] ?? '',
            'jadwal' => [],
        ];

        foreach ($rows as $row) {
            if (!empty($row['hari'])) {
                $result['jadwal'][] = [
                    'hari' => $row['hari'],
                    'jam' => $row['waktu'],
                ];
            }
        }

        sendResponse($result, 'Detail jadwal praktek berhasil diambil');
    }

    $query = "
        SELECT
            tp.id AS tempat_id,
            tp.nama_tempat,
            tp.alamat,
            tp.telp,
            tp.gambar,
            wp.hari,
            wp.waktu
        FROM tempat_praktek tp
        LEFT JOIN waktu_praktek wp ON tp.id = wp.tempat_id
        ORDER BY tp.nama_tempat, wp.hari, wp.waktu
    ";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($rows as $row) {
        $id = $row['tempat_id'];
        if (!isset($data[$id])) {
            $data[$id] = [
                'id' => $id,
                'nama' => $row['nama_tempat'],
                'alamat' => $row['alamat'],
                'imageUrl' => $row['gambar'] ?? '',
                'jadwal' => [],
            ];
        }
        if (!empty($row['hari'])) {
            $data[$id]['jadwal'][] = [
                'hari' => $row['hari'],
                'jam' => $row['waktu'],
            ];
        }
    }

    sendResponse(array_values($data), 'Daftar jadwal praktek berhasil diambil');
} catch (PDOException $e) {
    sendError('Kesalahan database: ' . $e->getMessage(), 500);
}
