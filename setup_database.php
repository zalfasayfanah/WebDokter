<?php
// Script untuk setup database medical_website
try {
    // Koneksi tanpa database untuk membuat database
    $pdo = new PDO("mysql:host=localhost;port=3307", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS medical_website");
    echo "Database medical_website berhasil dibuat/ditemukan.\n";
    
    // Pilih database
    $pdo->exec("USE medical_website");
    
    // Baca dan jalankan script SQL
    $sql = file_get_contents('config/database_rs.sql');
    
    // Split SQL statements
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Skip error untuk statement yang sudah ada
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "Tabel dan data berhasil dibuat.\n";
    
    // Jalankan script insert penyakit
    $insert_sql = file_get_contents('kategoriPenyakit/insert_penyakit.sql');
    $insert_statements = explode(';', $insert_sql);
    
    foreach ($insert_statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "Insert Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "Data penyakit berhasil ditambahkan.\n";
    echo "Setup database selesai!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>