<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/Koneksi.php';

function getConnection()
{
    $database = new Database();
    $conn = $database->getConnection();
    if (!$conn) {
        sendError('Tidak dapat terhubung ke database', 500);
    }
    return $conn;
}

function sendResponse($data, $message = 'Berhasil', $code = 200)
{
    http_response_code($code);
    echo json_encode([
        'success' => true,
        'message' => $message,
        'data' => $data,
    ]);
    exit;
}

function sendError($message = 'Terjadi kesalahan', $code = 400)
{
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $message,
        'data' => null,
    ]);
    exit;
}
