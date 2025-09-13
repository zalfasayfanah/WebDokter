<?php 
  include 'includes/NavbarUser.php'; 
  include 'config/Koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Layanan Dokter Arif Rahman</title>
  <link rel="stylesheet" href="assets/css/Pelayanan.css">
</head>
<body>

    <!-- Hero -->
    <div class="hero">
      <h1>LAYANAN KESEHATAN UNGGULAN OLEH DOKTER ARIF RAHMAN Sp.PD</h1>
      <p>
        Dapatkan layanan kesehatan lengkap untuk Anda dan keluarga bersama dr. Arif Rahman. 
        Mulai dari <strong>Poliklinik Penyakit Dalam</strong> untuk pemeriksaan menyeluruh, 
        <strong>Terapi Stemcell</strong> untuk membantu regenerasi sel tubuh, 
        <strong>Home Care</strong> profesional yang nyaman di rumah Anda, 
        hingga <strong>Telekonsultasi</strong> praktis yang bisa diakses kapan saja, di mana saja.
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
  <!-- ====== Footer ====== -->
  <?php include 'includes/footer.php'; ?>
</body>
</html>
