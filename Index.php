<?php 
  include 'config/Koneksi.php';
  include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/Index.css">

  

  <!-- ====== Beranda ====== -->
  <section id="beranda" class="hero">
    <div class="hero-text">
      <h1>dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua</h1>
      <h3>Spesialis Penyakit Dalam</h3>
      <p>Berpengalaman lebih dari 15 tahun dalam menangani berbagai penyakit dalam seperti diabetes, hipertensi, penyakit jantung, gangguan pencernaan, dan penyakit autoimun. Berkomitmen memberikan pelayanan kesehatan terbaik dengan pendekatan yang komprehensif dan personal.</p>
      <a href="#" class="btn-profile">Lihat Profile</a>
    </div>
    <div class="hero-img">
      <img src="assets/images/dokter.jpg" alt="Foto Dokter">
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
  <?php include 'includes/footer.php'; ?>

</body>
</html>
