<?php
/**
 * Mobile API Client Configuration
 * File ini berisi konfigurasi untuk koneksi dari aplikasi mobile
 */

// Konfigurasi API Server
const API_CONFIG = [
    // Development
    'development' => [
        'baseUrl' => 'http://localhost/WebDokter/api',
        'timeout' => 30000,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]
    ],
    
    // Production
    'production' => [
        'baseUrl' => 'https://yourdomain.com/api',
        'timeout' => 30000,
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]
    ]
];

// Aktifkan environment (development/production)
define('ENVIRONMENT', 'development');
define('API_BASE_URL', API_CONFIG[ENVIRONMENT]['baseUrl']);
define('API_TIMEOUT', API_CONFIG[ENVIRONMENT]['timeout']);

// Endpoints
const ENDPOINTS = [
    'organs' => '/organ.php',
    'diseases' => '/penyakit.php',
    'doctor' => '/profil_dokter.php',
    'schedules' => '/jadwal_praktek.php',
    'services' => '/pelayanan.php'
];

// Response codes
const RESPONSE_CODES = [
    'SUCCESS' => 200,
    'BAD_REQUEST' => 400,
    'UNAUTHORIZED' => 401,
    'NOT_FOUND' => 404,
    'SERVER_ERROR' => 500
];

/**
 * Helper: Build Full API URL
 * @param string $endpoint
 * @param array $params
 * @return string
 */
function buildApiUrl($endpoint, $params = []) {
    $url = API_BASE_URL . $endpoint;
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    return $url;
}

/**
 * Helper: Make API Request
 * @param string $endpoint
 * @param string $method
 * @param array $params
 * @param array $data
 * @return array|false
 */
function makeApiRequest($endpoint, $method = 'GET', $params = [], $data = []) {
    $url = buildApiUrl($endpoint, $params);
    
    $options = [
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json\r\n',
            'timeout' => API_TIMEOUT / 1000
        ]
    ];
    
    if (!empty($data) && in_array($method, ['POST', 'PUT'])) {
        $options['http']['content'] = json_encode($data);
    }
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return false;
    }
    
    return json_decode($response, true);
}
