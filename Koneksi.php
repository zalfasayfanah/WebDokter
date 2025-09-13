<?php
$host = "localhost:3307";
$user = "root";
$pass = "";


$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
