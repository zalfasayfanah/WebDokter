<?php 
  include 'config/Koneksi.php';
  include 'includes/header.php';
?>
<body>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/Pelayanan.css">

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
        <img src="assets/images/poli.png" alt="Poli Klinik">
        <p>Poli Klinik Sepesialis<br>Penyakit Dalam</p>
      </div>

      <div class="card">
        <img src="assets/images/stemcell.png" alt="Terapi Stemcell">
        <p>Terapi Regeneratif<br>(Exosome & Secretome)</p>
      </div>

      <div class="card">
  <img src="assets/images/homecare.png" alt="Home Care">
  <p>Home Care<br>(Via Rumah Sakit UNIMUS)</p>
</div>

      <div class="card">
        <img src="assets/images/konsultasi.png" alt="konsultasi">
        <p>Telekonsultasi<br>(Via Humas Rumah Sakit UNIMUS)</p>
      </div>
    </div>
  <!-- ====== Footer ====== -->
  <?php include 'includes/footer.php'; ?>
</body>
</html>
