<?php 
  include 'config/Koneksi.php';
  include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Pelayanan - dr. Arif Rahman, Sp.PD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #ffffff;
            color: #1e293b;
            line-height: 1.6;
            overflow-x: hidden;
            width: 100%;
        }

        /* Navbar Styles */
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        .navbar .logo {
            font-weight: bold;
            background-color: #f7c948;
            padding: 10px 24px;
            border-radius: 25px;
            color: #1a237e;
            font-size: 1.1rem;
            white-space: nowrap;
            z-index: 1001;
        }
        
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        
        .navbar ul li a {
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #f7c948;
            color: #1a237e;
        }

        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
            background: transparent;
            border: none;
        }

        .menu-toggle span {
            width: 30px;
            height: 3px;
            background: #f7c948;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .navbar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(3px);
        }

        .navbar-overlay.active {
            display: block;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(to bottom, #1e40af 0%, #1e3a8a 100%);
            padding: 60px 24px 50px;
            text-align: center;
            width: 100%;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
        }

        .hero h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            word-wrap: break-word;
        }

        .hero p {
            margin: 0 auto;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.8;
            font-weight: 400;
        }

        .hero p strong {
            color: #fbbf24;
            font-weight: 600;
        }

        /* Section Title */
        .section-title {
            text-align: center;
            margin: 60px 0 40px;
            padding: 0 24px;
        }

        .section-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: #173f9cff;
            margin: 0;
        }

        /* Cards Grid */
        .cards {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px 100px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
            width: 100%;
        }
        .card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    position: relative;
}

        

        .card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            position: relative;
          
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0c4a6e 0%, #0284c7 100%);
        }

        .icon-container {
            width: 100%;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            overflow: hidden;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .card-content {
            padding: 28px 24px;
            background: #ffffff;
            text-align: center;
        }

        .card-content p {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: #062b83ff;
            line-height: 1.5;
        }

        /* Trust Badge */
        .trust-section {
            max-width: 1400px;
            margin: 0 auto 80px;
            padding: 0 24px;
            text-align: center;
        }

        .trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: #f0f9ff;
            padding: 18px 36px;
            border-radius: 50px;
            border: 2px solid #bae6fd;
        }

        .trust-badge-icon {
            width: 28px;
            height: 28px;
            background: #0284c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .trust-badge-text {
            font-size: 16px;
            color: #2780b4ff;
            font-weight: 600;
        }

        /* Tablet */
        @media screen and (max-width: 1024px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 0 20px 80px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .section-title h2 {
                font-size: 24px;
            }
        }

        /* Mobile - TETAP 2 KOLOM HORIZONTAL */
        @media screen and (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }

            .navbar .logo {
                font-size: 0.95rem;
                padding: 8px 18px;
            }

            .menu-toggle {
                display: flex;
            }

            .navbar ul {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                height: 100vh;
                background: linear-gradient(180deg, #1a3c92 0%, #0d2459 100%);
                flex-direction: column;
                gap: 0;
                padding: 80px 0 20px 0;
                transition: left 0.4s ease;
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
                overflow-y: auto;
            }

            .navbar ul.active {
                left: 0;
            }

            .navbar ul li {
                width: 100%;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .navbar ul li a {
                display: block;
                padding: 18px 25px;
                border-radius: 0;
                width: 100%;
                font-size: 1rem;
            }

            .navbar ul li a:hover {
                background-color: rgba(247, 201, 72, 0.15);
                color: #f7c948;
                padding-left: 35px;
            }

            .hero {
                padding: 50px 20px 40px;
            }

            .hero h1 {
                font-size: 24px;
                margin-bottom: 16px;
            }

            .hero p {
                font-size: 14px;
                line-height: 1.7;
            }

            .section-title {
                margin: 50px 0 35px;
                padding: 0 20px;
            }

            .section-title h2 {
                font-size: 20px;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
                padding: 0 15px 60px;
                gap: 12px;
            }

            .icon-container {
                height: 160px;
            }

            .card-content {
                padding: 16px 12px;
            }

            .card-content p {
                font-size: 13px;
                line-height: 1.4;
            }

            .card-content p br {
                display: inline;
                content: " ";
            }

            .trust-badge {
                padding: 14px 28px;
            }

            .trust-badge-icon {
                width: 24px;
                height: 24px;
                font-size: 16px;
            }

            .trust-badge-text {
                font-size: 14px;
            }
        }

        /* Small Mobile - TETAP 2 KOLOM */
        @media screen and (max-width: 480px) {
            .navbar {
                padding: 12px 15px;
            }

            .navbar .logo {
                font-size: 0.85rem;
                padding: 7px 15px;
            }

            .menu-toggle span {
                width: 26px;
                height: 2.5px;
            }

            .navbar ul {
                width: 260px;
            }

            .hero {
                padding: 40px 16px 35px;
            }

            .hero h1 {
                font-size: 20px;
            }

            .hero p {
                font-size: 13px;
            }

            .section-title {
                margin: 40px 0 30px;
                padding: 0 16px;
            }

            .section-title h2 {
                font-size: 18px;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
                padding: 0 10px 50px;
                gap: 10px;
            }

            .card {
                border-width: 1.5px;
            }

            .icon-container {
                height: 140px;
            }

            .card-content {
                padding: 14px 10px;
            }

            .card-content p {
                font-size: 12px;
                line-height: 1.3;
            }

            .trust-badge {
                padding: 12px 24px;
            }

            .trust-badge-icon {
                width: 22px;
                height: 22px;
                font-size: 14px;
            }

            .trust-badge-text {
                font-size: 13px;
            }
        }

        /* Extra Small Mobile - TETAP 2 KOLOM */
        @media screen and (max-width: 360px) {
            .navbar {
                padding: 10px 12px;
            }

            .navbar .logo {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .navbar ul {
                width: 240px;
            }

            .hero h1 {
                font-size: 18px;
            }

            .hero p {
                font-size: 12px;
            }

            .section-title h2 {
                font-size: 16px;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
                padding: 0 8px 40px;
                gap: 8px;
            }

            .icon-container {
                height: 120px;
            }

            .card-content {
                padding: 12px 8px;
            }

            .card-content p {
                font-size: 11px;
                line-height: 1.3;
            }

            .trust-badge {
                padding: 10px 20px;
            }

            .trust-badge-icon {
                width: 20px;
                height: 20px;
                font-size: 12px;
            }

            .trust-badge-text {
                font-size: 12px;
            }
  
        }
    </style>
</head>
<body>
    <!-- Navbar Overlay -->
    <div class="navbar-overlay" id="navbarOverlay"></div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>LAYANAN KESEHATAN DOKTER ARIF RAHMAN Sp.PD</h1>
            <p>
                Dapatkan layanan kesehatan lengkap untuk Anda dan keluarga bersama dr. Arif Rahman. 
                Mulai dari <strong>Poliklinik Penyakit Dalam</strong> untuk pemeriksaan menyeluruh, 
                <strong>Terapi Stemcell</strong> untuk membantu regenerasi sel tubuh, 
                <strong>Home Care</strong> profesional yang nyaman di rumah Anda, 
                hingga <strong>Telekonsultasi</strong> praktis yang bisa diakses kapan saja, di mana saja.
            </p>
        </div>
    </div>

    <!-- Section Title -->
    <div class="section-title">
        <h2>Layanan Medis Penyakit Dalam Yang Ditawarkan</h2>
    </div>

    <!-- Cards -->
    <div class="cards">
        <div class="card">
            <div class="icon-container">
                <img src="assets/images/poliklinik.jpeg" alt="Poli Klinik Penyakit Dalam">
            </div>
            <div class="card-content">
                <p>Poli Klinik Spesialis<br>Penyakit Dalam</p>
            </div>
        </div>

        <div class="card">
            <div class="icon-container">
                <img src="assets/images/stemcell.jpeg" alt="Terapi Regeneratif">
            </div>
            <div class="card-content">
                <p>Terapi Regeneratif<br>(Exosome & Secretome)</p>
            </div>
        </div>

        <div class="card">
            <div class="icon-container">
                <img src="assets/images/homecare.jpeg" alt="Layanan Home Care">
            </div>
            <div class="card-content">
                <p>Home Care<br>(Via Rumah Sakit UNIMUS)</p>
            </div>
        </div>

        <div class="card">
            <div class="icon-container">
                <img src="assets/images/telekonsultasi.jpeg" alt="Telekonsultasi Online">
            </div>
            <div class="card-content">
                <p>Telekonsultasi<br>(Via Humas RS UNIMUS)</p>
            </div>
        </div>
    </div>

    <!-- Trust Badge -->
    <div class="trust-section">
        <div class="trust-badge">
            <div class="trust-badge-icon">✓</div>
            <span class="trust-badge-text">Layanan Terpercaya & Profesional</span>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Toggle menu mobile
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        const navbarOverlay = document.getElementById('navbarOverlay');

        if (menuToggle && navMenu && navbarOverlay) {
            function toggleMenu() {
                menuToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
                navbarOverlay.classList.toggle('active');
                
                if (navMenu.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }

            menuToggle.addEventListener('click', toggleMenu);

            navbarOverlay.addEventListener('click', function() {
                toggleMenu();
            });

            document.querySelectorAll('.navbar ul li a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        toggleMenu();
                    }
                });
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    navMenu.classList.remove('active');
                    menuToggle.classList.remove('active');
                    navbarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
    </script>
</body>
</html>