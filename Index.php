<?php
include 'config/Koneksi.php';
include 'includes/header.php';
?>
<?php

$database = new Database();
$db = $database->getConnection();

// Query untuk mengambil data jadwal praktek
$query = "
    SELECT 
        tp.id AS tempat_id,
        tp.nama_tempat,
        tp.alamat,
        tp.telp,
        tp.gmaps_link,
        tp.gambar,
        GROUP_CONCAT(CONCAT(wp.hari, '|', wp.waktu) ORDER BY wp.hari SEPARATOR ';;') AS jadwal
    FROM tempat_praktek tp
    LEFT JOIN waktu_praktek wp ON tp.id = wp.tempat_id
    GROUP BY tp.id, tp.nama_tempat, tp.alamat, tp.telp, tp.gmaps_link, tp.gambar
    ORDER BY tp.nama_tempat
";

$stmt = $db->prepare($query);
$stmt->execute();
$jadwalPraktek = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="assets/css/Index.css">



<!-- ====== Beranda ====== -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Arif Rahman, Sp.PD - Spesialis Penyakit Dalam</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e3a8a;
            background-color: #f8fafc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */


        .doctor-name {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e3a8a;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .doctor-name:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 0;
            align-items: center;
        }

        .nav-links li {
            margin: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            padding: 15px 25px;
            transition: all 0.3s ease;
            border-radius: 8px;
            display: block;
        }

        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .mobile-menu {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
            color: white;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 4rem 0 2rem;
            /* dikurangi supaya tidak terlalu tinggi */
            margin-top: 0;
            /* hilangkan margin kosong di atas */
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 3rem;
            /* lebih rapat sedikit */
            align-items: center;
        }

        .hero-text h1 {
            font-size: 2.5rem;
            /* agak kecil biar proporsional */
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: #fbbf24;
            margin-bottom: 1.2rem;
            font-weight: 600;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }


        .cta-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .cta-button {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e3a8a;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
        }

        .cta-button.secondary {
            background: transparent;
            color: #1e3a8a;
            border: 2px solid #1e3a8a;
        }

        .cta-button.secondary:hover {
            background: #1e3a8a;
            color: white;
        }

        .hero-image {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

            .doctor-photo {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border: 6px solid #fbbf24; /* warna emas */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;

        }

           .doctor-photo img {
            width: 100%;       /* zoom foto */
            height: auto;
            object-fit: cover;
            border-radius: 70%;
            transform: translateY(10px) translateX(-20px); 
            /* 👉 ubah -10px untuk naik/turun 
            👉 ubah X untuk geser kiri/kanan */
        }


        .quick-info {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .quick-info h4 {
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: #64748b;
        }

        /* Doctor Profile Section */
        .doctor-profile {
            padding: 4rem 0;
            background: white;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 4rem;
            align-items: start;
        }

        .profile-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(30, 58, 138, 0.3);
        }

        .profile-photo {
            width: 150px;                /* ukuran lingkaran */
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fbbf24;         /* kuning biar sama seperti sebelumnya */
        }

        .profile-photo img {
            width: 110%;                 /* perbesar foto sedikit */
            height: auto;
            object-fit: cover;
            border-radius: 50%;
            transform: translateY(10px) translateX(-17px); /* naikkan foto supaya wajah lebih center */
        }


        .profile-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #fbbf24;
        }

        .profile-card .specialty {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: #e2e8f0;
        }

        .credentials {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }

        .credentials h4 {
            color: #fbbf24;
            margin-bottom: 0.5rem;
        }

        .credentials ul {
            list-style: none;
            font-size: 0.9rem;
        }

        .credentials li {
            margin-bottom: 0.3rem;
        }

        .profile-info {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 15px;
        }

        .section-title {
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #64748b;
            margin-bottom: 2rem;
        }

        .specializations {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .specialization-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #fbbf24;
            transition: transform 0.3s ease;
        }

        .specialization-card:hover {
            transform: translateY(-3px);
        }

        .specialization-card h4 {
            color: #1e3a8a;
            margin-bottom: 0.5rem;
        }

        .specialization-card p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Experience Section */
        .experience {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f8fafc 0%, white 100%);
        }

        .experience-timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-line {
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #fbbf24;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 3rem;
            position: relative;
        }

        .timeline-marker {
            width: 60px;
            height: 60px;
            background: #1e3a8a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fbbf24;
            font-size: 1.5rem;
            z-index: 2;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .timeline-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin-left: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .timeline-content h4 {
            color: #1e3a8a;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .timeline-date {
            color: #fbbf24;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .timeline-content p {
            color: #64748b;
            line-height: 1.6;
        }



        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e3a8a;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #fbbf24;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .submit-btn {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e3a8a;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
        }



        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .doctor-name {
                font-size: 0.9rem;
                padding: 10px 20px;
            }

            nav {
                padding: 0 15px;
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1.3rem;
            }

            .profile-content {
                grid-template-columns: 1fr;
            }

            .contact-content {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta-buttons {
                justify-content: center;
            }

            .timeline-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .timeline-content {
                margin-left: 0;
                margin-top: 1rem;
            }

            .timeline-line {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .doctor-name {
                font-size: 0.8rem;
                padding: 8px 16px;
            }
        }

        /* ====== Section Jadwal ====== */
        .judul-jadwal {
            background-color: #1a3c92;
            /* biru tua */
            color: #fff;
            /* teks putih */
            padding: 15px 30px;
            /* jarak dalam */
            border-radius: 40px;
            /* sudut melengkung */
            text-align: center;
            /* rata tengah */
            font-weight: bold;
            font-size: 30px;
            margin: 40px auto 20px auto;
            /* jarak luar */
            width: 90%;
            /* panjang bar */
        }

        .judul-jadwal h2 {
            margin: 0;
            /* biar teks nempel tanpa jarak ekstra */
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 320px;
            text-align: left;
            border-bottom: 6px solid #f7c948;
            /* garis kuning bawah */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 6px solid #f7c948;
            /* garis kuning kiri */
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card:hover {
            transform: translateY(-10px);
            /* naik saat hover */
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
            /* bayangan lebih tebal */
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
            margin-bottom: 20px;
            /* paksa hilang margin bawaan semua paragraf */
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
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Dr. Arif Rahman</h1>
                    <p class="hero-subtitle">Spesialis Penyakit Dalam</p>
                    <p>Dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua
                        Spesialis Penyakit Dalam
                        Berpengalaman lebih dari 10 tahun dalam menangani berbagai penyakit dalam seperti diabetes, hipertensi, penyakit jantung, gangguan pencernaan, hingga penyakit autoimun, Dr. Arif Rahman telah membantu ribuan pasien mendapatkan pelayanan kesehatan terbaik. Beliau juga aktif mengembangkan terapi medis inovatif dan pengobatan regeneratif dengan metode stem cell dan turunannya seperti exosome serta secretome untuk mendukung penyembuhan yang lebih optimal.
                        Dengan berbagai pelatihan nasional maupun internasional, serta fellowship di bidang nutrisi, olahraga klinis, dan mutu layanan kesehatan, Dr. Arif Rahman berkomitmen memberikan layanan kesehatan yang komprehensif, personal, dan berstandar tinggi bagi masyarakat.
                        </p>
                    <div class="cta-buttons">

                        <a href="#profile" class="cta-button secondary">Lihat Profil</a>
                    </div>
                </div>
                <div class="hero-image">
                   <div class="doctor-photo">
                       <img src="assets/images/dokter-removebg-preview.png" alt="Foto Dokter" />
                    </div>
                    <div class="quick-info">
                        <h4>Informasi Singkat</h4>
                        <div class="info-item">
                            <span>👨‍⚕️</span>
                            <span>dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua</span>
                        </div>
                        <div class="info-item">
                            <span>💉</span>
                            <span>Spesialis Penyakit Dalam & Terapi Regeneratif</span>
                        </div>
                        <div class="info-item">
                            <span>⏳</span>
                            <span>10+ Tahun Pengalaman</span>
                        </div>
                         <div class="info-item">
                            <span>🏥 </span>
                            <span>Praktik di: RS KUSUMA Ungaran & RS UNIMUS Semarang</span>
                        </div>
                        <div class="info-item">
                            <span>📍</span>
                            <span>Gmaps: dr. Arif Rahman Sp.PD, FINASIM, FINEM, AIFO-K, FISQua (Dokter Spesialis Penyakit Dalam & Dokter Stemcell) Kota Semarang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctor Profile Section -->
   <section id="profile" class="doctor-profile">
    <div class="container">
        <div class="profile-content">
            
            <!-- Kartu Profil Dokter -->
            <div class="profile-card">
                <div class="profile-photo"><img src="assets/images/dokter-removebg-preview.png" alt="Foto Dokter" /></div>
                <h3>Dr. Arif Rahman</h3>
                <p class="specialty">Sp.PD</p>

                <div class="credentials">
                    <h4>Pendidikan:</h4>
                    <ul>
                        <li>• Pendidikan Kedokteran – Universitas Sebelas Maret (UNS) Surakarta</li>
                        <li>• Spesialis Penyakit Dalam – Universitas Diponegoro Semarang</li>
                    </ul>
                </div>

                <div class="credentials">
                    <h4>Organisasi:</h4>
                    <ul>
                        <li>• Ikatan Dokter Indonesia (IDI)</li>
                        <li>• Perhimpunan Dokter Spesialis Penyakit Dalam Indonesia (PAPDI)</li>
                    </ul>
                </div>
            </div>

            <!-- Informasi Detail -->
            <div class="profile-info">
                <h2 class="section-title">Tentang Dokter</h2>
                <p class="about-text">
                   dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua adalah dokter spesialis penyakit dalam dengan pengalaman lebih dari 10 tahun dalam menangani penyakit lansia (Geriatri), diabetes, hipertensi, autoimun, gangguan pencernaan, serta pola makan sehat.
                    Beliau menyelesaikan pendidikan Kedokteran Umum di Universitas Diponegoro (2014) dan melanjutkan Spesialis Penyakit Dalam di Universitas Sebelas Maret (2021).
                    Selain praktik klinis, dr. Arif aktif mengembangkan terapi regeneratif seperti Stem Cell dan Exosome, mengikuti berbagai pelatihan terapi regeneratif, dan berkomitmen memberikan pelayanan kesehatan yang komprehensif serta berbasis bukti ilmiah terkini.
                    Saat ini, dr. Arif berpraktik di RS Kusuma Ungaran dan RS UNIMUS Semarang, serta merupakan anggota Perhimpunan Dokter Spesialis Penyakit Dalam Indonesia (PAPDI) dan Perhimpunan Dokter Seminat Rekayasa Jaringan dan Terapi Sel Indonesia (REJASELINDO).
                </p>
                <p class="about-text">
                    Dedikasi beliau adalah memberikan pelayanan kesehatan terbaik, berbasis bukti, dengan pendekatan yang 
                    humanis dan penuh kepedulian terhadap pasien.
                </p>

                <h3 style="color: #1e3a8a; margin: 2rem 0 1rem 0;">Spesialis & Keahlian</h3>
                <div class="specializations">
                    <div class="specialization-card">
                        <h4>🩺 Penyakit Dalam Umum</h4>
                        <p>Diagnosis dan tata laksana berbagai penyakit dalam secara komprehensif.</p>
                    </div>
                    <div class="specialization-card">
                        <h4>❤️ Hipertensi & Penyakit Jantung</h4>
                        <p>Penanganan hipertensi, penyakit jantung koroner, dan pencegahan komplikasi kardiovaskular.</p>
                    </div>
                    <div class="specialization-card">
                        <h4>🍽️ Gangguan Pencernaan</h4>
                        <p>GERD, gastritis, penyakit hati, dan berbagai gangguan saluran cerna.</p>
                    </div>
                    <div class="specialization-card">
                        <h4>🧬 Penyakit Metabolik</h4>
                        <p>Gangguan tiroid, obesitas, diabetes melitus, dan sindrom metabolik.</p>
                    </div>
                    <div class="specialization-card">
                        <h4>🫁 Penyakit Paru</h4>
                        <p>Asma, PPOK, pneumonia, dan gangguan pernapasan lainnya.</p>
                    </div>
                    <div class="specialization-card">
                        <h4>🔄 Penyakit Autoimun</h4>
                        <p>Lupus, rheumatoid arthritis, dan berbagai gangguan sistem imun.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

    <!-- Experience Timeline -->
   <section class="experience">
    <div class="container">
        <h2 class="section-title" style="text-align: center;">Riwayat Pekerjaan</h2>
        <div class="experience-timeline">
            <div class="timeline-line"></div>

            <!-- Pendidikan Kedokteran -->
            <div class="timeline-item">
                <div class="timeline-marker">🎓</div>
                <div class="timeline-content">
                    <h4>Pendidikan Kedokteran</h4>
                    <div class="timeline-date">1996 - 2002</div>
                    <p>Program Pendidikan Dokter Umum di Fakultas Kedokteran Universitas Diponegoro, Semarang</p>
                </div>
            </div>

            <!-- Spesialisasi Penyakit Dalam -->
            <div class="timeline-item">
                <div class="timeline-marker">🎓</div>
                <div class="timeline-content">
                    <h4>Spesialis Penyakit Dalam</h4>
                    <div class="timeline-date">2003 - 2008</div>
                    <p>Pendidikan Spesialis Penyakit Dalam di Fakultas Kedokteran Universitas Diponegoro, Semarang</p>
                </div>
            </div>

            <!-- Dokter Spesialis -->
            <div class="timeline-item">
                <div class="timeline-marker">🏥</div>
                <div class="timeline-content">
                    <h4>Dokter Spesialis Penyakit Dalam</h4>
                    <div class="timeline-date">2008 - 2015</div>
                    <p>Praktik sebagai dokter spesialis penyakit dalam di berbagai rumah sakit di Semarang dan sekitarnya</p>
                </div>
            </div>

            <!-- Konsultan Senior -->
            <div class="timeline-item">
                <div class="timeline-marker">⭐</div>
                <div class="timeline-content">
                    <h4>Konsultan Senior</h4>
                    <div class="timeline-date">2015 - Sekarang</div>
                    <p>Bekerja sebagai konsultan senior penyakit dalam, menangani pasien, memberikan edukasi, dan aktif dalam organisasi profesi IDI & PAPDI</p>
                </div>
            </div>

        </div>
    </div>
</section>



    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.backgroundColor = 'rgba(30, 58, 138, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.backgroundColor = '';
                header.style.backdropFilter = '';
            }
        });
    </script>

    <!-- ====== Jadwal Praktek ====== -->
    <section id="jadwal" class="jadwal">
    <div class="judul-jadwal">
        <h2>Jadwal Praktek</h2>
    </div>
    <p>Berikut adalah jadwal praktek dokter yang dapat Anda kunjungi di beberapa lokasi pelayanan kesehatan.</p>

    <div class="jadwal-cards">
        <?php foreach ($jadwalPraktek as $tempat): ?>
            <!-- Card -->
            <div class="card">
                <a href="<?= htmlspecialchars($tempat['gmaps_link'] ?? '#') ?>" target="_blank" style="text-decoration:none; color:inherit;">
                    <?php if (!empty($tempat['gambar'])): ?>
                        <img src="admin/assets/images/<?= htmlspecialchars($tempat['gambar']) ?>" alt="<?= htmlspecialchars($tempat['nama_tempat']) ?>">
                    <?php else: ?>
                        <img src="assets/images/default-hospital.jpg" alt="Default Image">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h3>
                            <i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>
                            <?= htmlspecialchars($tempat['nama_tempat']) ?>
                        </h3>
                        <p class="alamat">
                            <?= htmlspecialchars($tempat['alamat']) ?><br>
                            Telp: <?= htmlspecialchars($tempat['telp']) ?>
                        </p>
                    </div>
                </a>
                
                <table>
                    <tr>
                        <th>Hari</th>
                        <th>Waktu</th>
                    </tr>
                    <?php 
                    // Parse jadwal yang digabung
                    if (!empty($tempat['jadwal'])) {
                        $jadwalList = explode(';;', $tempat['jadwal']);
                        foreach ($jadwalList as $jadwal) {
                            list($hari, $waktu) = explode('|', $jadwal);
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($hari) . '</td>';
                            echo '<td>' . htmlspecialchars($waktu) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="2" class="text-center">Tidak ada jadwal</td></tr>';
                    }
                    ?>
                </table>
            </div>
        <?php endforeach; ?>

        <?php if (empty($jadwalPraktek)): ?>
            <div class="col-12 text-center">
                <p>Belum ada jadwal praktek yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
    <!-- ====== FOOOTERRR ====== -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>