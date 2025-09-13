<style>
/* ====== Navbar ====== */
    .navbar {
      position: sticky;   /* biar ikut pas scroll */
      top: 0;
      z-index: 1000;
      background-color: #1a3c92;
      padding: 25px 50px; /* dilebarin ke bawah */
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
</style>
  <!-- ====== Navbar ====== -->
  <nav class="navbar">
    <div class="logo">Panel Admin</div>
    <ul>
      <li><a href="#beranda">Beranda</a></li>
      <li><a href="#jadwal">Jadwal Praktek</a></li>
      <li><a href="kategoriPenyakit/penyakit.php">Penyakit Dalam</a></li>
      <li><a href="Pelayanan.php">Pelayanan</a></li>
    </ul>
  </nav>