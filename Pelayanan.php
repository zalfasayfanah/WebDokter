<?php 
  include 'NavbarUser.php'; 
  include 'Koneksi.php';
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Layanan Dokter Arif Rahman</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #ffffff;
      color: #333;
    }
    a {
      text-decoration: none;
      color: inherit;
    }

    /* ====== Navbar ====== */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      background-color: #1a3c92;
      padding: 20px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
    }

    .navbar .logo {
      font-weight: bold;
      background-color: #f7c948;
      padding: 8px 20px;
      border-radius: 25px;
      color: #1a237e;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    .navbar ul li a {
      color: #fff;
      font-weight: 500;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .navbar ul li a:hover,
    .navbar ul li a:active {
      background-color: #f7c948;
      color: #1a237e;
    }

    /* Hero Section */
    .hero {
      background: #1f3d91;
      color: white;
      padding: 40px 20px;
      text-align: center;
    }

    .hero h1 {
      margin: 0 0 20px 0;
      font-size: 22px;
      font-weight: bold;
    }

    .hero p {
      max-width: 850px;
      margin: 0 auto;
      font-size: 14px;
      line-height: 1.6;
    }

    /* Section Layanan */
    .section-title {
      text-align: center;
      margin: 40px 0 20px 0;
      font-size: 20px;
      font-weight: bold;
      color: #333;
    }

    .cards {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
      padding: 20px;
    }

    .card {
      background: #163785;
      border: 3px solid #f6b400;
      border-radius: 12px;
      width: 210px;
      text-align: center;
      padding: 25px 15px;
      color: white;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.25);
    }

    .card img {
      width: 65px;
      height: 65px;
      margin-bottom: 15px;
    }

    .card p {
      margin: 0;
      font-size: 14px;
      font-weight: bold;
      line-height: 1.4;
    }
  </style>
</head>
<body>

  <!-- Hero -->
  <div class="hero">
    <h1>LAYANAN KESEHATAN UNGGULAN OLEH DOKTER ARIF RAHMAN Sp.PD</h1>
    <p>
      Dapatkan layanan kesehatan lengkap untuk Anda dan keluarga bersama dr. Arif Rahman. 
      Mulai dari Poliklinik Penyakit Dalam untuk pemeriksaan menyeluruh, Terapi Stemcell 
      untuk membantu regenerasi sel tubuh, Home Care profesional yang nyaman di rumah Anda, 
      hingga Telekonsultasi praktis yang bisa diakses kapan saja, di mana saja.
    </p>
  </div>

  <!-- Layanan -->
  <h2 class="section-title">Layanan Medis Penyakit Dalam Yang Ditawarkan</h2>

  <div class="cards">
    <div class="card">
      <img src="https://img.icons8.com/color/96/medical-doctor.png" alt="Poli Klinik">
      <p>Poli Klinik<br>Penyakit Dalam</p>
    </div>

    <div class="card">
      <img src="https://img.icons8.com/color/96/dna-helix.png" alt="Terapi Stemsel">
      <p>Terapi<br>Stemsel</p>
    </div>

    <div class="card">
      <img src="https://img.icons8.com/color/96/kidney.png" alt="Home Care">
      <p>Home Care<br>(Via Rumah Sakit UNIMUS)</p>
    </div>

    <div class="card">
      <img src="https://img.icons8.com/color/96/online-support.png" alt="Telekonsultasi">
      <p>Telekonsultasi<br>(Via Rumah Sakit UNIMUS)</p>
    </div>
  </div>

</body>
</html>
