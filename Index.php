<?php
include 'config/Koneksi.php';
include 'includes/header.php';

function getImagePath($filename)
{
    if (empty($filename)) {
        return 'admin/assets/images/default-hospital.jpg';
    }

    // Bersihkan path yang mungkin sudah tersimpan di database
    $cleanFilename = str_replace([
        'assets/images/',
        '../assets/images/',
        'admin/assets/images/',
        '/'
    ], '', $filename);

    // Return path relatif dari index.php ke folder gambar admin
    return 'admin/assets/images/' . $cleanFilename;
}

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

// Query untuk mengambil data organisasi
$queryOrganisasi = "SELECT * FROM organisasi ORDER BY nama_organisasi";
$stmtOrg = $db->prepare($queryOrganisasi);
$stmtOrg->execute();
$organisasiList = $stmtOrg->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="assets/css/Index.css">

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Arif Rahman, Sp.PD - Spesialis Penyakit Dalam</title>
    <style>
        
        {
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

        .doctor-name {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e3a8a;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
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
            margin-top: 0;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 3rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 2.5rem;
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
    transition: background 0.3s ease, color 0.3s ease;
    text-decoration: none;
    display: inline-block;
    transform: none !important;
}

.cta-button:hover {
    transform: none !important;
    box-shadow: none !important;
}

.cta-button.secondary {
    background: transparent;
    color: #1e3a8a;
    border: 2px solid #1e3a8a;
    width: 200px;
    height: 150px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 0.5rem;
    text-transform: none;
    letter-spacing: normal;
}

.button-title {
    font-size: 1.1rem;
    font-weight: bold;
}

.button-desc {
    font-size: 0.75rem;
    font-weight: normal;
    line-height: 1.3;
    opacity: 0.8;
}

.cta-button.secondary:hover {
    background: #1e3a8a;
    color: white;
    transform: none !important;
}

.cta-button.secondary:hover .button-desc {
    opacity: 1;
}

/* Judul */
.cta-title {
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 6px;
    letter-spacing: 1px;
}

/* Deskripsi */
.cta-desc {
    font-size: 0.75rem;
    font-weight: normal;
    line-height: 1.4;
    color: #1e3a8a;
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
            border: 6px solid #fbbf24;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .doctor-photo img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 70%;
            transform: translateY(10px) translateX(-20px);
        }

       .quick-info {
    background: rgba(255, 255, 255, 0.1);
    padding: 16px;
    border-radius: 10px;
}

.quick-info h4 {
    margin-bottom: 10px;
    font-weight: 600;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 8px;
}

.info-item i {
    font-size: 16px;
    min-width: 22px;
    text-align: center;
    margin-top: 2px;
}

.info-item span {
    font-size: 0.9rem;
    line-height: 1.5;
    text-align: left;
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
            overflow: hidden;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fbbf24;
        }

        .profile-photo img {
            width: 110%;
            height: auto;
            object-fit: cover;
            border-radius: 50%;
            transform: translateY(10px) translateX(-17px);
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
        .credentials ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .credentials li {
            padding-left: 1.2rem;      /* jarak teks dari bullet */
            text-indent: -1.2rem;      /* tarik bullet ke kiri */
            line-height: 1.6;
            margin-bottom: 0.4rem;
            text-align: left;
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

        /* Section Jadwal */
        .judul-jadwal {
            background-color: #1a3c92;
            color: #fff;
            padding: 15px 30px;
            border-radius: 40px;
            text-align: center;
            font-weight: bold;
            font-size: 30px;
            margin: 40px auto 20px auto;
            width: 90%;
        }

        .judul-jadwal h2 {
            margin: 0;
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 6px solid #f7c948;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .cta-buttons {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 10px;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cta-button.secondary {
            background: linear-gradient(145deg, #1e3a8a, #3b82f6);
            color: #ffffff;
            border: none;
            box-shadow:
                0 8px 15px rgba(30, 58, 138, 0.4),
                0 4px 6px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        .cta-button.secondary:hover {
            background: linear-gradient(145deg, #1e40af, #2563eb);
            transform: translateY(-4px);
            box-shadow:
                0 12px 25px rgba(37, 99, 235, 0.5),
                0 8px 10px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                inset 0 -2px 5px rgba(0, 0, 0, 0.25);
        }

        .cta-button.secondary:active {
            transform: translateY(-1px);
            box-shadow:
                0 4px 10px rgba(30, 58, 138, 0.3),
                0 2px 4px rgba(0, 0, 0, 0.1),
                inset 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Perbaikan untuk tampilan full dengan jarak yang sesuai */
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }

        html {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        header,
        nav {
            margin: 0 !important;
            width: 100% !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }

        .container {
            max-width: 1200px !important;
            /* Batasi lebar maksimal */
            margin: 0 auto !important;
            /* Tengah otomatis */
            padding: 0 20px !important;
            /* Jarak kiri-kanan 20px */
        }

        /* Hero full tapi konten ada jarak */
        .hero {
            width: 100% !important;
            margin: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .hero .container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 40px !important;
            /* Jarak lebih besar untuk hero */
        }

        section {
            width: 100% !important;
            margin: 0 !important;
        }

        /* Untuk section dengan background, biarkan full */
        section.jadwal,
        section.experience,
        section.organisasi {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Tapi konten di dalamnya tetap ada jarak */
        section.jadwal .container,
        section.experience .container,
        section.organisasi .container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 40px !important;
        }
        
        /* ============================================
   RESPONSIVE MOBILE - GANTI @media YANG LAMA
   ============================================ */

/* Hapus @media (max-width: 768px) dan @media (max-width: 480px) yang lama,
   lalu ganti dengan kode di bawah ini */

@media (max-width: 768px) {
    /* Reset Container */
    .container {
        padding: 0 15px !important;
        width: 100% !important;
    }

    /* Navigation */
    .doctor-name {
        font-size: 0.85rem;
        padding: 8px 16px;
    }

    .nav-links {
        display: none;
    }

    .mobile-menu {
        display: block;
    }

    /* Hero Section */
    .hero {
        padding: 2rem 0 1.5rem !important;
    }

    .hero .container {
        padding: 0 20px !important;
    }

    .hero-content {
        grid-template-columns: 1fr !important;
        gap: 2rem;
    }

    .hero-text {
        text-align: center;
    }

    .hero-text h1 {
        font-size: 1.8rem !important;
        margin-bottom: 0.8rem;
    }

    .hero-subtitle {
        font-size: 1.05rem !important;
        margin-bottom: 1rem;
    }

    .hero-text p {
        font-size: 0.9rem !important;
        line-height: 1.6;
        text-align: justify;
    }

    /* CTA Buttons Mobile */
    .cta-buttons {
        justify-content: center;
        width: 100%;
        gap: 0.8rem;
    }

    .cta-button.secondary {
        width: calc(50% - 0.5rem) !important;
        min-width: 140px;
        height: auto !important;
        min-height: 100px;
        padding: 0.8rem !important;
    }

    .button-title {
        font-size: 0.95rem !important;
    }

    .button-desc {
        font-size: 0.68rem !important;
        line-height: 1.2;
    }

    /* Hero Image Card */
    .hero-image {
        padding: 1.5rem;
        margin: 0 auto;
        max-width: 100%;
    }

    .doctor-photo {
        width: 170px !important;
        height: 170px !important;
        margin: 0 auto 1.2rem;
    }

    .doctor-photo img {
        transform: translateY(8px) translateX(-15px);
    }

    .quick-info {
        padding: 14px;
    }

    .quick-info h4 {
        font-size: 0.95rem;
        margin-bottom: 8px;
    }

    .info-item {
        gap: 8px;
        margin-bottom: 6px;
    }

    .info-item i {
        font-size: 14px;
        min-width: 20px;
    }

    .info-item span {
        font-size: 0.82rem;
        line-height: 1.4;
    }

    /* Doctor Profile Section */
    .doctor-profile {
        padding: 2.5rem 0 !important;
    }

    .profile-content {
        grid-template-columns: 1fr !important;
        gap: 2rem;
    }

    .profile-card {
        padding: 1.5rem;
        margin: 0 auto;
        max-width: 100%;
    }

    .profile-photo {
        width: 130px !important;
        height: 130px !important;
    }

    .profile-photo img {
        transform: translateY(8px) translateX(-14px);
    }

    .profile-card h3 {
        font-size: 1.3rem;
    }

    .profile-card .specialty {
        font-size: 0.95rem;
    }

    .credentials {
        padding: 1rem;
        margin: 1.2rem 0;
    }

    .credentials h4 {
        font-size: 0.95rem;
        margin-bottom: 8px;
    }

    .credentials li {
        font-size: 0.85rem;
        line-height: 1.5;
        padding-left: 1rem;
        text-indent: -1rem;
    }

    /* Profile Info */
    .profile-info {
        padding: 1.5rem;
    }

    .section-title {
        font-size: 1.8rem !important;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .about-text {
        font-size: 0.9rem !important;
        line-height: 1.65;
        text-align: justify;
        margin-bottom: 1.5rem;
    }

    .profile-info h3 {
        font-size: 1.2rem !important;
        text-align: center;
    }

    /* Specializations */
    .specializations {
        grid-template-columns: 1fr !important;
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .specialization-card {
        padding: 1.2rem;
    }

    .specialization-card h4 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .specialization-card p {
        font-size: 0.88rem;
        line-height: 1.5;
    }

    /* Experience Timeline */
    .experience {
        padding: 2.5rem 0 !important;
    }

    .experience .container {
        padding: 0 20px !important;
    }

    .experience-timeline {
        padding: 0;
    }

    .timeline-line {
        display: none;
    }

    .timeline-item {
        flex-direction: column;
        margin-bottom: 2rem;
        align-items: flex-start;
    }

    .timeline-marker {
        width: 55px !important;
        height: 55px !important;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        align-self: flex-start;
    }

    .timeline-content {
        margin-left: 0 !important;
        padding: 1.3rem;
        width: 100%;
    }

    .timeline-content h4 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .timeline-date {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .timeline-content p {
        font-size: 0.88rem;
        line-height: 1.6;
    }

    /* Organisasi Section */
    .organisasi {
        padding: 2.5rem 0 !important;
    }

    .organisasi .container {
        padding: 0 20px !important;
    }

    .organisasi .section-title {
        font-size: 1.6rem !important;
    }

    .organisasi > .container > p {
        font-size: 0.95rem !important;
        margin-bottom: 2rem !important;
    }

    .org-item {
        padding: 1.3rem !important;
        gap: 1rem !important;
        margin-bottom: 1.2rem !important;
        flex-direction: row !important;
    }

    .org-item > div:first-child {
        min-width: 48px !important;
        height: 48px !important;
        font-size: 1.2rem !important;
        flex-shrink: 0;
    }

    .org-item h4 {
        font-size: 1rem !important;
        margin-bottom: 0.3rem !important;
    }

    .org-item p {
        font-size: 0.85rem !important;
    }

    /* Jadwal Section */
    .judul-jadwal {
        width: 95% !important;
        padding: 12px 20px !important;
        font-size: 1.4rem !important;
        margin: 25px auto 15px auto !important;
        border-radius: 30px;
    }

    .judul-jadwal h2 {
        font-size: 1.4rem;
    }

    .jadwal {
        padding: 2rem 15px !important;
    }

    .jadwal h2 {
        font-size: 1.4rem !important;
    }

    .jadwal p {
        font-size: 0.9rem !important;
        padding: 0 10px;
        margin-bottom: 25px !important;
        line-height: 1.5;
    }

    .jadwal-cards {
        gap: 18px;
        padding: 0;
    }

    .card {
        width: 100% !important;
        max-width: 400px;
        margin: 0 auto;
    }

    .card img {
        height: 170px !important;
    }

    .card-body {
        padding: 16px !important;
    }

    .card-body h3 {
        font-size: 1rem !important;
        margin-bottom: 5px;
    }

    .card-body h3 i {
        font-size: 0.9rem;
    }

    .alamat {
        font-size: 0.8rem !important;
        line-height: 1.4;
        margin-bottom: 10px !important;
    }

    table {
        font-size: 0.82rem !important;
        margin-top: 10px;
    }

    table th,
    table td {
        padding: 7px 6px !important;
    }

    table th {
        font-size: 0.85rem;
    }
}

/* Mobile Kecil (480px ke bawah) */
@media (max-width: 480px) {
    .container {
        padding: 0 12px !important;
    }

    .doctor-name {
        font-size: 0.78rem;
        padding: 7px 14px;
    }

    /* Hero */
    .hero {
        padding: 1.5rem 0 1rem !important;
    }

    .hero .container {
        padding: 0 15px !important;
    }

    .hero-text h1 {
        font-size: 1.5rem !important;
    }

    .hero-subtitle {
        font-size: 0.95rem !important;
    }

    .hero-text p {
        font-size: 0.85rem !important;
    }

    /* CTA Buttons */
    .cta-buttons {
        gap: 0.7rem;
    }

    .cta-button.secondary {
        width: 100% !important;
        max-width: 100%;
        min-height: 95px;
        padding: 0.7rem !important;
    }

    .button-title {
        font-size: 0.9rem !important;
    }

    .button-desc {
        font-size: 0.65rem !important;
    }

    /* Hero Image */
    .doctor-photo {
        width: 150px !important;
        height: 150px !important;
    }

    .quick-info h4 {
        font-size: 0.9rem;
    }

    .info-item span {
        font-size: 0.78rem;
    }

    /* Profile */
    .profile-photo {
        width: 110px !important;
        height: 110px !important;
    }

    .profile-card h3 {
        font-size: 1.2rem;
    }

    .profile-card .specialty {
        font-size: 0.9rem;
    }

    .credentials {
        padding: 0.8rem;
    }

    .credentials h4 {
        font-size: 0.9rem;
    }

    .credentials li {
        font-size: 0.8rem;
    }

    .section-title {
        font-size: 1.5rem !important;
    }

    .about-text {
        font-size: 0.85rem !important;
    }

    /* Timeline */
    .timeline-marker {
        width: 45px !important;
        height: 45px !important;
        font-size: 1.1rem;
    }

    .timeline-content {
        padding: 1rem;
    }

    .timeline-content h4 {
        font-size: 1rem;
    }

    .timeline-content p {
        font-size: 0.82rem;
    }

    /* Organisasi */
    .org-item {
        padding: 1rem !important;
    }

    .org-item > div:first-child {
        min-width: 42px !important;
        height: 42px !important;
        font-size: 1rem !important;
    }

    .org-item h4 {
        font-size: 0.95rem !important;
    }

    .org-item p {
        font-size: 0.8rem !important;
    }

    /* Jadwal */
    .judul-jadwal {
        font-size: 1.2rem !important;
        padding: 10px 15px !important;
    }

    .judul-jadwal h2 {
        font-size: 1.2rem;
    }

    .jadwal {
        padding: 1.5rem 10px !important;
    }

    .jadwal h2 {
        font-size: 1.2rem !important;
    }

    .jadwal p {
        font-size: 0.85rem !important;
    }

    .card {
        max-width: 100%;
    }

    .card img {
        height: 150px !important;
    }

    .card-body {
        padding: 14px !important;
    }

    .card-body h3 {
        font-size: 0.95rem !important;
    }

    .alamat {
        font-size: 0.75rem !important;
    }

    table {
        font-size: 0.78rem !important;
    }

    table th,
    table td {
        padding: 6px 4px !important;
    }
}

/* Landscape Mode */
@media (max-width: 768px) and (orientation: landscape) {
    .hero {
        padding: 1.5rem 0 !important;
    }

    .hero-content {
        gap: 1.5rem;
    }

    .doctor-photo {
        width: 140px !important;
        height: 140px !important;
    }

    .hero-text h1 {
        font-size: 1.6rem !important;
    }

    .cta-button.secondary {
        min-height: 90px;
    }
}
/* === JADWAL HORIZONTAL SCROLL - MOBILE === */
@media (max-width: 768px) {
    /* Jadwal Section */
    .judul-jadwal {
        width: 90%;
        padding: 12px 20px;
        font-size: 1.4rem;
        margin: 25px auto 15px auto;
    }

    .jadwal {
        padding: 2rem 0 !important;
        overflow: visible !important;
    }

    .jadwal h2 {
        font-size: 1.4rem;
        padding: 0 15px;
    }

    .jadwal > p {
        font-size: 0.9rem;
        padding: 0 15px;
        margin-bottom: 25px;
    }

    /* Container Cards - HORIZONTAL SCROLL */
    .jadwal-cards {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        overflow-x: scroll !important;
        overflow-y: visible !important;
        gap: 15px !important;
        padding: 10px 15px 30px 15px !important;
        margin: 0 !important;
        -webkit-overflow-scrolling: touch;
        justify-content: flex-start !important;
    }

    /* Hide scrollbar */
    .jadwal-cards::-webkit-scrollbar {
        display: none;
    }

    /* Each Card */
    .jadwal-cards .card {
        flex: 0 0 280px !important;
        min-width: 280px !important;
        max-width: 280px !important;
        width: 280px !important;
        margin: 0 !important;
        display: block !important;
    }

    .card img {
        height: 170px;
    }

    .card-body {
        padding: 16px;
    }

    .card-body h3 {
        font-size: 1rem;
    }

    .alamat {
        font-size: 0.8rem;
    }

    table {
        font-size: 0.82rem;
    }

    table th,
    table td {
        padding: 7px 6px;
    }
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
    <a href="#" class="cta-button secondary">
        <span class="button-title">Geriatri</span>
        <span class="button-desc">Layanan kesehatan khusus untuk lansia</span>
    </a>
</div>
<div class="cta-buttons">
    <a href="#" class="cta-button secondary">
        <span class="button-title">Osteoarthritis</span>
        <span class="button-desc">Perawatan nyeri sendi dan tulang</span>
    </a>
</div>
<div class="cta-buttons">
    <a href="#" class="cta-button secondary">
        <span class="button-title">Diabetes</span>
        <span class="button-desc">Pengelolaan gula darah terpadu</span>
    </a>
</div>
                </div>
                <div class="hero-image">
                    <div class="doctor-photo">
                        <img src="assets/images/dokter-removebg-preview.png" alt="Foto Dokter" />
                    </div>
                  <div class="quick-info">
                  <h4>Informasi Singkat</h4>
                <div class="info-item">
                    <i class="fas fa-user-md"></i>
                    <span>dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua</span>
                </div>

                <div class="info-item">
                    <i class="fas fa-stethoscope"></i>
                    <span>Spesialis Penyakit Dalam & Terapi Regeneratif</span>
                </div>

                <div class="info-item">
                    <i class="fas fa-hourglass-half"></i>
                    <span>10+ Tahun Pengalaman</span>
                </div>
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
                            <li>• Pendidikan Kedokteran Universitas Sebelas Maret (UNS) Surakarta</li>
                            <li>• Spesialis Penyakit Dalam Universitas Diponegoro Semarang</li>
                        </ul>
                    </div>

                    <div class="credentials">
                        <h4>Organisasi:</h4>
                        <ul>
                            <?php
                            if (!empty($organisasiList)) {
                                foreach ($organisasiList as $org) {
                                    echo '<li>• ' . htmlspecialchars($org['nama_organisasi']) . '</li>';
                                }
                            } else {
                                echo '<li>• Belum ada data organisasi</li>';
                            }
                            ?>
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

                <div class="timeline-item">
                    <div class="timeline-marker">🎓</div>
                    <div class="timeline-content">
                        <h4>Pendidikan Kedokteran</h4>
                        <div class="timeline-date">1996 - 2002</div>
                        <p>Program Pendidikan Dokter Umum di Fakultas Kedokteran Universitas Diponegoro, Semarang</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">🎓</div>
                    <div class="timeline-content">
                        <h4>Spesialis Penyakit Dalam</h4>
                        <div class="timeline-date">2003 - 2008</div>
                        <p>Pendidikan Spesialis Penyakit Dalam di Fakultas Kedokteran Universitas Diponegoro, Semarang</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker">🏥</div>
                    <div class="timeline-content">
                        <h4>Dokter Spesialis Penyakit Dalam</h4>
                        <div class="timeline-date">2008 - 2015</div>
                        <p>Praktik sebagai dokter spesialis penyakit dalam di berbagai rumah sakit di Semarang dan sekitarnya</p>
                    </div>
                </div>

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

    <!-- ====== Section Organisasi ====== -->
    <section class="organisasi" style="padding: 4rem 0; background: linear-gradient(135deg, #f8fafc 0%, white 100%);">
        <div class="container">
            <h2 class="section-title" style="text-align: center; margin-bottom: 1rem;">Keanggotaan Organisasi Profesi</h2>
            <p style="text-align: center; color: #64748b; margin-bottom: 3rem; font-size: 1.1rem;">
                Dr. Arif Rahman aktif dalam berbagai organisasi profesi kedokteran
            </p>

            <div style="max-width: 800px; margin: 0 auto;">
                <?php foreach ($organisasiList as $index => $org): ?>
                    <div class="org-item" style="
                        background: white;
                        padding: 2rem;
                        border-radius: 15px;
                        margin-bottom: 1.5rem;
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                        display: flex;
                        align-items: center;
                        gap: 2rem;
                        transition: all 0.3s ease;
                        border-left: 6px solid #fbbf24;
                    "
                        style="box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

                        <div style="
                            min-width: 60px;
                            height: 60px;
                            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: #fbbf24;
                            font-size: 1.5rem;
                            font-weight: bold;
                        ">
                            <?= $index + 1 ?>
                        </div>

                        <div style="flex: 1;">
                            <h4 style="
                                color: #1e3a8a;
                                font-size: 1.3rem;
                                margin: 0 0 0.5rem 0;
                                font-weight: 600;
                            ">
                                <?= htmlspecialchars($org['nama_organisasi']) ?>
                            </h4>
                            <p style="
                                color: #64748b;
                                margin: 0;
                                font-size: 0.95rem;
                            ">
                                <span style="color: #fbbf24;">✓</span> Anggota Aktif
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($organisasiList)): ?>
                    <div style="
                        text-align: center; 
                        color: #64748b; 
                        padding: 3rem; 
                        background: white; 
                        border-radius: 15px;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                    ">
                        <p style="font-size: 1.1rem;">Belum ada data organisasi profesi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

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
                        <?php $imagePath = getImagePath($tempat['gambar'] ?? ''); ?>

                        <img src="<?= htmlspecialchars($imagePath) ?>"
                            alt="<?= htmlspecialchars($tempat['nama_tempat']) ?>"
                            onerror="this.src='admin/assets/images/default-hospital.jpg'"
                            style="width:100%; height:180px; object-fit:cover;">

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
                            echo '<tr><td colspan="2" style="text-align:center;">Tidak ada jadwal</td></tr>';
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
            if (header && window.scrollY > 100) {
                header.style.backgroundColor = 'rgba(30, 58, 138, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else if (header) {
                header.style.backgroundColor = '';
                header.style.backdropFilter = '';
            }
        });
    </script>

    <!-- ====== FOOTER ====== -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>