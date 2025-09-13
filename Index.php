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
  <title>Dokter Arif Rahman</title>
  <style>
    
    /* ====== Global Style ====== */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      color: #1a237e;
    }
    a {
      text-decoration: none;
      color: inherit;
    }

<<<<<<< HEAD:Index.html
    

        /* ====== Navbar ====== */
        
.navbar {
  position: sticky;   /* biar tetap di atas pas scroll */
  top: 0;
  z-index: 1000;
  background-color: #1a3c92;
  padding: 25px 50px;
  display: flex;
  justify-content: space-between; /* logo di kiri, menu di kanan */
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
      gap: 20px;  /* jarak antar menu lebih lega */
      margin: 0;
      padding: 0;
    }
    .navbar ul li a {
      color: #fff;
      font-weight: 500;
      text-decoration: none;
      padding: 8px 16px;       /* kasih area klik */
  border-radius: 5px;      /* biar smooth pas hover */
      transition: all 0.3s ease;
    }
    .navbar ul li a:hover,
.navbar ul li a:active {
  background-color: #f7c948; /* kuning */
  color: #1a237e;            /* teks biru tua */
}

=======
>>>>>>> 8ea446dd95edc4b4f4930338429cd68761bd5e00:Index.php

    /* ====== Section Beranda ====== */
    .hero {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 60px 80px;
      background-color: #fff;
    }
    .hero-text {
      max-width: 50%;
    }
    .hero-text h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .hero-text h3 {
      font-size: 20px;
      margin-bottom: 15px;
    }
    .hero-text p {
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .btn-profile {
      background-color: #f7c948;
      padding: 10px 20px;
      border-radius: 20px;
      color: #1a237e;
      font-weight: bold;
    }
    .hero-img img {
      width: 280px;
      border-radius: 50%;
      background-color: #f7c948;
      padding: 15px;
    }

    /* ====== Section Jadwal ====== */
    .judul-jadwal {
      background-color: #1a3c92;   /* biru tua */
      color: #fff;                 /* teks putih */
      padding: 15px 30px;                /* jarak dalam */
      border-radius: 40px;         /* sudut melengkung */
      text-align: center;          /* rata tengah */
      font-weight: bold;
      font-size: 30px;
      margin: 40px auto 20px auto; /* jarak luar */
      width: 90%;                  /* panjang bar */
    }
    .judul-jadwal h2 {
      margin: 0;  /* biar teks nempel tanpa jarak ekstra */
    }
    .jadwal {
      padding: 60px 80px;
      background-color: #f8f9fa;
      text-align: center;
    }
    .jadwal h2 {
      font-size: 28px;
      margin-bottom: 10px;
    }
    .jadwal p {
      margin-bottom: 40px;
      font-size: 16px;
      color: #333;
    }
    .jadwal-cards {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
      width: 320px;
      text-align: left;
      border-bottom: 6px solid #f7c948; /* garis kuning bawah */
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-left: 6px solid #f7c948; /* garis kuning kiri */
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .card:hover {
    transform: translateY(-10px); /* naik saat hover */
    box-shadow: 0 12px 20px rgba(0,0,0,0.2); /* bayangan lebih tebal */
    }
    .card-body {
      padding: 20px 20px 15px 20px;
    }
    .card-body h3 {
      font-size: 18px;
      margin-top: 1px;
      margin-bottom: 0;
    }
    .card-body p {
    margin-bottom: 20px; /* paksa hilang margin bawaan semua paragraf */
    }

    .alamat {
      font-size: 12px;
      margin-top: 3px;
      margin-bottom: 0px;
      color: #333;
      line-height: 1.2;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 0;
    }
    table th {
      background-color: #1a3c92;
      color: #f7c948;
      padding: 8px;
      text-align: left;
    }
    table td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    /* ====== Footer ====== */
    .footer {
      background-color: #1a3c92;
      color: #fff;
      padding: 40px 80px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    .footer .left, .footer .right {
      max-width: 45%;
    }
    .footer h3 {
      margin-bottom: 10px;
    }
    .footer a {
      color: #f7c948;
      display: block;
      margin-bottom: 5px;
    }
    .copyright {
      text-align: center;
      padding: 15px;
      background-color: #142b6f;
      color: #ddd;
      font-size: 14px;
    }
  </style>
</head>
<body>

<<<<<<< HEAD:Index.html
<!-- ====== Navbar ====== -->
<nav class="navbar">
  <div class="logo">dr. Arif Rahman, Sp.PD</div>
  <ul>
    <li><a href="#beranda">Beranda</a></li>
    <li><a href="#jadwal">Jadwal Praktek</a></li>
    <li><a href="Penyakit.html">Penyakit</a></li>
    <li><a href="Pelayanan.html">Pelayanan</a></li>
  </ul>
</nav>

=======
  
>>>>>>> 8ea446dd95edc4b4f4930338429cd68761bd5e00:Index.php

  <!-- ====== Beranda ====== -->
  <section id="beranda" class="hero">
    <div class="hero-text">
      <h1>dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua</h1>
      <h3>Spesialis Penyakit Dalam</h3>
      <p>Berpengalaman lebih dari 15 tahun dalam menangani berbagai penyakit dalam seperti diabetes, hipertensi, penyakit jantung, gangguan pencernaan, dan penyakit autoimun. Berkomitmen memberikan pelayanan kesehatan terbaik dengan pendekatan yang komprehensif dan personal.</p>
      <a href="#" class="btn-profile">Lihat Profile</a>
    </div>
    <div class="hero-img">
      <img src="dokter.png" alt="Foto Dokter">
    </div>
  </section>

  <!-- ====== Jadwal Praktek ====== -->
  <section id="jadwal" class="jadwal">
    <div class="judul-jadwal">
        <h2>Jadwal Praktek</h2>
    </div>
    <p>Berikut adalah jadwal praktek dokter yang dapat Anda kunjungi di beberapa lokasi pelayanan kesehatan.</p>
       <div class="jadwal-cards">
      
      <!-- Card 1 -->
      <div class="card">
        <a href="https://www.google.com/maps/dir//Jl.+Kedungmundu+No.214,+Kedungmundu,+Kec.+Tembalang,+Kota+Semarang,+Jawa+Tengah+50273/@-7.0221268,110.3763543,12z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x2e708d90c74616d1:0x4b34a66d9f9be2ae!2m2!1d110.4587562!2d-7.022134?entry=ttu&g_ep=EgoyMDI1MDkwNy4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="text-decoration:none; color:inherit;">
        <img src="assets/images/RSUNIMUS.webp" alt="RS Unimus">
        <div class="card-body">
          <h3><i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>Rumah Sakit Unimus</h3>
          <p class="alamat">Jl. Kedungmundu No.214, Tembalang, Kota Semarang<br>Telp: 0812345678910</p>
          </a>
          <table>
            <tr><th>Hari</th><th>Waktu</th></tr>
            <tr><td>Senin</td><td>13:00 - 15:00</td></tr>
            <tr><td>Selasa</td><td>13:00 - 15:00</td></tr>
            <tr><td>Rabu</td><td>13:00 - 15:00</td></tr>
          </table>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="card">
        <a href="https://www.google.com/maps?um=1&ie=UTF-8&fb=1&gl=id&sa=X&geocode=KVG4M7SniHAuMXf-_PQ5m47h&daddr=Jl.+Letjend+Suprapto+No.62,+Paren,+Sidomulyo,+Kec.+Ungaran+Tim.,+Kabupaten+Semarang,+Jawa+Tengah+50514" target="_blank" style="text-decoration:none; color:inherit;">
        <img src="assets/images/RSKUSUMA.webp" alt="RS Kusuma Ungaran">
        <div class="card-body">
          <h3><i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>Rumah Sakit Kusuma Ungaran</h3>
          <p class="alamat">Jl. Kedungmundu No.214, Tembalang, Kota Semarang<br>Telp: 0812345678910</p>
          </a>
          <table>
            <tr><th>Hari</th><th>Waktu</th></tr>
            <tr><td>Senin - Jumat</td><td>18:00 - 21:00</td></tr>
          </table>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="card">
        <a href="https://www.google.com/maps/dir//Jl.+Petek+Jl.+Kp.+Gayam,+RT.02%2FRW.06,+Dadapsari,+Kec.+Semarang+Utara,+Kota+Semarang,+Jawa+Tengah+50173/@-6.9679227,110.3370586,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x2e70f514ab8380d5:0x7fde5e6fc3fbbf9f!2m2!1d110.4194605!2d-6.9679298?entry=ttu&g_ep=EgoyMDI1MDkwNy4wIKXMDSoASAFQAw%3D%3D" target="_blank" style="text-decoration:none; color:inherit;">
        <img src="assets\images\KLINIKPRATAMA.webp" alt="Klinik Pratama Unimus">
        <div class="card-body">
          <h3><i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>Klinik Pratama Unimus</h3>
          <p class="alamat">Jl. Kedungmundu No.214, Tembalang, Kota Semarang<br>Telp: 0812345678910</p>
          </a>
          <table>
            <tr><th>Hari</th><th>Waktu</th></tr>
            <tr><td>Senin</td><td>13:00 - 15:00</td></tr>
            <tr><td>Selasa</td><td>13:00 - 15:00</td></tr>
          </table>
        </div>
      </div>

    </div>
  </section>

  <!-- ====== Footer ====== -->
  <footer>
    <div class="footer">
      <div class="left">
        <h3>Dokter Spesialis Penyakit Dalam</h3>
        <p>Melayani dengan sepenuh hati untuk kesehatan anda</p>
        <a href="#">facebook.com/dokter</a>
        <a href="#">@dokter (Instagram)</a>
        <a href="#">@dokter (TikTok)</a>
        <a href="#">youtube.com/dokter</a>
      </div>
      <div class="right">
        <h3>Kontak Kami</h3>
        <p>Jl. Kedungmundu No.24, Tembalang, Kota Semarang</p>
        <p>0812-3456-7890</p>
        <p>dokter@gmail.com</p>
      </div>
    </div>
    <div class="copyright">
      © 2025 Dokter Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua. Semua Hak Dilindungi.
    </div>
  </footer>

</body>
</html>
