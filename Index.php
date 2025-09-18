<?php
include 'config/Koneksi.php';
include 'includes/header.php';
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
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
            border: 6px solid #fbbf24;
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
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #fbbf24;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #1e3a8a;
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
                    <p>Dokter spesialis penyakit dalam lulusan FK UNS tahun 2021 dengan berbagai sertifikasi fellowship internasional. Berpengalaman menangani berbagai penyakit dalam dan saat ini menjabat sebagai Manajer Pelayanan & Penunjang Medis di RS UNIMUS Semarang. Berkomitmen memberikan pelayanan kesehatan terbaik dengan pendekatan yang komprehensif dan personal.</p>
                    <div class="cta-buttons">

                        <a href="#profile" class="cta-button secondary">Lihat Profil</a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="doctor-photo">👨‍⚕️</div>
                    <div class="quick-info">
                        <h4>Informasi Singkat</h4>
                        <div class="info-item">
                            <span>🎓</span>
                            <span>Sp.PD, FINASIM, FINEM, AIFO-K, FISQua</span>
                        </div>
                        <div class="info-item">
                            <span>⏰</span>
                            <span>3+ Tahun Pengalaman</span>
                        </div>
                        <div class="info-item">
                            <span>📞</span>
                            <span>0812-2515-3406</span>
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
                <div class="profile-card">
                    <div class="profile-photo">👨‍⚕️</div>
                    <h3>Dr. Arif Rahman</h3>
                    <p class="specialty">Sp.PD, FINASIM</p>

                    <div class="credentials">
                        <h4>Sertifikasi:</h4>
                        <ul>
                            <li>• Spesialis Penyakit Dalam</li>
                            <li>• Fellow Penyakit Dalam</li>
                            <li>• Sertifikat Diabetes</li>
                            <li>• Sertifikat Kardiologi</li>
                        </ul>
                    </div>

                    <div class="credentials">
                        <h4>Anggota:</h4>
                        <ul>
                            <li>• PAPDI (Perhimpunan Ahli Penyakit Dalam Indonesia)</li>
                            <li>• PERKENI (Perkumpulan Endokrinologi Indonesia)</li>
                            <li>• IDI (Ikatan Dokter Indonesia)</li>
                        </ul>
                    </div>
                </div>

                <div class="profile-info">
                    <h2 class="section-title">Tentang Dokter</h2>
                    <p class="about-text">
                        Dr. Arif Rahman adalah seorang dokter spesialis penyakit dalam yang berpengalaman lebih dari 15 tahun. Beliau menyelesaikan pendidikan kedokteran di Universitas Indonesia dan melanjutkan spesialisasi penyakit dalam di Fakultas Kedokteran yang sama.
                    </p>
                    <p class="about-text">
                        Dr. Arif memiliki keahlian khusus dalam penanganan penyakit diabetes melitus, hipertensi, penyakit jantung koroner, gangguan pencernaan, penyakit ginjal, dan berbagai penyakit autoimun. Beliau juga aktif dalam penelitian dan telah mempublikasikan berbagai artikel ilmiah di jurnal nasional dan internasional.
                    </p>

                    <h3 style="color: #1e3a8a; margin: 2rem 0 1rem 0;">Keahlian Khusus</h3>
                    <div class="specializations">
                        <div class="specialization-card">
                            <h4>🩺 Diabetes Melitus</h4>
                            <p>Pengelolaan diabetes tipe 1 dan 2, pencegahan komplikasi, dan edukasi gaya hidup sehat</p>
                        </div>
                        <div class="specialization-card">
                            <h4>❤️ Penyakit Kardiovaskular</h4>
                            <p>Hipertensi, penyakit jantung koroner, gagal jantung, dan pencegahan penyakit kardiovaskular</p>
                        </div>
                        <div class="specialization-card">
                            <h4>🔄 Penyakit Autoimun</h4>
                            <p>Lupus, rheumatoid arthritis, dan berbagai gangguan sistem imun lainnya</p>
                        </div>
                        <div class="specialization-card">
                            <h4>🫁 Penyakit Paru</h4>
                            <p>Asma, PPOK, pneumonia, dan gangguan sistem pernapasan</p>
                        </div>
                        <div class="specialization-card">
                            <h4>🍽️ Gangguan Pencernaan</h4>
                            <p>GERD, gastritis, IBS, penyakit hati, dan gangguan saluran cerna</p>
                        </div>
                        <div class="specialization-card">
                            <h4>🧬 Penyakit Metabolik</h4>
                            <p>Gangguan tiroid, obesitas, sindrom metabolik, dan gangguan hormon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Timeline -->
    <section class="experience">
        <div class="container">
            <h2 class="section-title" style="text-align: center;">Riwayat Karir</h2>
            <div class="experience-timeline">
                <div class="timeline-line"></div>

                <div class="timeline-item">
                    <div class="timeline-marker">🎓</div>
                    <div class="timeline-content">
                        <h4>Dokter Spesialis Penyakit Dalam</h4>
                        <div class="timeline-date">2005 - 2009</div>
                        <p>Program Pendidikan Dokter Spesialis Penyakit Dalam, Fakultas Kedokteran Universitas Indonesia</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">🏥</div>
                    <div class="timeline-content">
                        <h4>Dokter Spesialis - RS Cipto Mangunkusumo</h4>
                        <div class="timeline-date">2009 - 2015</div>
                        <p>Dokter spesialis penyakit dalam di departemen penyakit dalam, menangani kasus-kasus kompleks dan mengajar mahasiswa kedokteran</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">🔬</div>
                    <div class="timeline-content">
                        <h4>Fellowship Endokrinologi</h4>
                        <div class="timeline-date">2015 - 2016</div>
                        <p>Program fellowship khusus endokrinologi dan diabetes, memperdalam keahlian dalam penanganan penyakit hormonal</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">⭐</div>
                    <div class="timeline-content">
                        <h4>Konsultan Senior - RS Prima Medika</h4>
                        <div class="timeline-date">2016 - Sekarang</div>
                        <p>Konsultan senior penyakit dalam, memimpin tim medis, dan mengembangkan program pencegahan penyakit tidak menular</p>
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

            <!-- Card 1 -->
            <div class="card">
                <a href="https://www.google.com/maps/dir//Jl.+Kedungmundu+No.214..." target="_blank" style="text-decoration:none; color:inherit;">
                    <img src="assets/images/RSUNIMUS.webp" alt="RS Unimus">
                    <div class="card-body">
                        <h3><i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>Rumah Sakit Unimus</h3>
                        <p class="alamat">Jl. Kedungmundu No.214, Tembalang, Kota Semarang<br>Telp: 0812345678910</p>
                    </div>
                </a>
                <table>
                    <tr>
                        <th>Hari</th>
                        <th>Waktu</th>
                    </tr>
                    <tr>
                        <td>Senin</td>
                        <td>13:00 - 15:00</td>
                    </tr>
                    <tr>
                        <td>Selasa</td>
                        <td>13:00 - 15:00</td>
                    </tr>
                    <tr>
                        <td>Rabu</td>
                        <td>13:00 - 15:00</td>
                    </tr>
                </table>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <a href="https://www.google.com/maps?um=1&ie=UTF-8..." target="_blank" style="text-decoration:none; color:inherit;">
                    <img src="assets/images/RSKUSUMA.webp" alt="RS Kusuma Ungaran">
                    <div class="card-body">
                        <h3><i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>Rumah Sakit Kusuma Ungaran</h3>
                        <p class="alamat">Jl. Letjend Suprapto No.62, Ungaran<br>Telp: 0812345678910</p>
                    </div>
                </a>
                <table>
                    <tr>
                        <th>Hari</th>
                        <th>Waktu</th>
                    </tr>
                    <tr>
                        <td>Senin - Jumat</td>
                        <td>18:00 - 21:00</td>
                    </tr>
                </table>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <a href="https://www.google.com/maps/dir//Jl.+Petek..." target="_blank" style="text-decoration:none; color:inherit;">
                    <img src="assets/images/KLINIKPRATAMA.webp" alt="Klinik Pratama Unimus">
                    <div class="card-body">
                        <h3><i class="fa-solid fa-location-dot" style="color:#f7c948; margin-right:8px;"></i>Klinik Pratama Unimus</h3>
                        <p class="alamat">Jl. Kedungmundu No.214, Tembalang, Kota Semarang<br>Telp: 0812345678910</p>
                    </div>
                </a>
                <table>
                    <tr>
                        <th>Hari</th>
                        <th>Waktu</th>
                    </tr>
                    <tr>
                        <td>Sabtu</td>
                        <td>09:00 - 12:00</td>
                    </tr>
                </table>
            </div>

        </div>
    </section>
    <!-- ====== FOOOTERRR ====== -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>