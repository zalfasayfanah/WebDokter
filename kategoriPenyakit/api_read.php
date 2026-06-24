<?php
// Header wajib agar aplikasi mobile mengenali ini sebagai JSON
header("Content-Type: application/json; charset=UTF-8");

// Hubungkan ke database (sesuaikan dengan file config Anda)
include_once("../config/database.php"); 

// Query sederhana
$query = "SELECT * FROM kategori_penyakit";
$result = mysqli_query($conn, $query);

$data = array();
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

// Kirim hasil sebagai JSON
echo json_encode($data);
?>