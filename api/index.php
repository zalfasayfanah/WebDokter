<?php
/**
 * WebDokter API Gateway
 * Main entry point for mobile API
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// API endpoints documentation
$endpoints = [
    [
        'method' => 'GET',
        'endpoint' => '/api/organ.php',
        'description' => 'Ambil daftar kategori organ aktif',
        'example' => 'GET /api/organ.php'
    ],
    [
        'method' => 'GET',
        'endpoint' => '/api/penyakit.php',
        'description' => 'Ambil semua penyakit aktif',
        'example' => 'GET /api/penyakit.php',
        'parameters' => [
            'organId (optional)' => 'Filter penyakit berdasarkan ID organ',
            'id (optional)' => 'Ambil detail penyakit berdasarkan ID'
        ]
    ],
    [
        'method' => 'GET',
        'endpoint' => '/api/detail_penyakit.php',
        'description' => 'Ambil detail penyakit berdasarkan ID dari tabel penyakit',
        'example' => 'GET /api/detail_penyakit.php?id={id}',
        'parameters' => [
            'id (required)' => 'ID penyakit'
        ]
    ],
    [
        'method' => 'GET',
        'endpoint' => '/api/profil_dokter.php',
        'description' => 'Ambil profil dokter beserta riwayat pendidikan, sertifikat, dan keahlian khusus',
        'example' => 'GET /api/profil_dokter.php'
    ],
    [
        'method' => 'GET',
        'endpoint' => '/api/jadwal_praktek.php',
        'description' => 'Ambil jadwal praktik dokter',
        'example' => 'GET /api/jadwal_praktek.php',
        'parameters' => [
            'id (optional)' => 'Ambil detail jadwal berdasarkan ID'
        ]
    ],
    [
        'method' => 'GET',
        'endpoint' => '/api/pelayanan.php',
        'description' => 'Ambil daftar layanan medis aktif',
        'example' => 'GET /api/pelayanan.php'
    ]
];

// Check if requesting API documentation
if ($request === '/WebDokter/api' || $request === '/WebDokter/api/' || $request === '/api/index.php') {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'WebDokter API Gateway v1.0',
        'version' => '1.0',
        'description' => 'REST API untuk menghubungkan aplikasi mobile dengan WebDokter',
        'baseUrl' => getBaseUrl(),
        'endpoints' => $endpoints,
        'documentation' => getBaseUrl() . '/../api/README.md'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// Default response for root
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (
    $request === '' || 
    $request === '/' || 
    strpos($request, 'index.php') !== false
)) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'WebDokter API Gateway v1.0',
        'version' => '1.0',
        'description' => 'REST API untuk menghubungkan aplikasi mobile dengan WebDokter',
        'baseUrl' => getBaseUrl(),
        'endpoints' => $endpoints,
        'documentation' => getBaseUrl() . '/../api/README.md'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// If endpoint not found
http_response_code(404);
echo json_encode([
    'status' => 'error',
    'message' => 'Endpoint tidak ditemukan',
    'requested' => $request,
    'documentation' => getBaseUrl() . '/../api/README.md'
]);

/**
 * Helper function to get base URL
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . $host . '/WebDokter/api';
}
