<?php
require_once '../config/Koneksi.php';
$database = new Database();
$db = $database->getConnection();

// Query data kategori organ dari tabel
$query = "SELECT * FROM kategori_organ_home WHERE status='aktif' ORDER BY id ASC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyakit Dalam</title>
    <link rel="stylesheet" href="../assets/css/style_fixed.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* === GLOBAL STYLE === */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #0a1a44;
        }

        /* === HERO SECTION === */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, #0a1a44 0%, #142e6e 50%, #1e3a8a 100%);
            color: #ffffff;
            text-align: center;
            padding: 7rem 2rem 9rem;
            overflow: hidden;
        }

        /* Cahaya lembut dekoratif */
        .hero-section::before,
        .hero-section::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(251,191,36,0.25), transparent);
            animation: float 8s ease-in-out infinite;
        }
        .hero-section::before {
            top: -100px;
            left: -100px;
            width: 250px;
            height: 250px;
        }
        .hero-section::after {
            bottom: -80px;
            right: -80px;
            width: 200px;
            height: 200px;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(15px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Animasi Fade In */
        .fade-in-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.25);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeSlideIn 1s ease forwards;
        }

        .fade-in-desc {
            font-size: 1.15rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeSlideIn 1.2s ease forwards;
            animation-delay: 0.4s;
        }

        .fade-in-search {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeSlideIn 1.2s ease forwards;
            animation-delay: 0.8s;
        }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* === SEARCH BAR === */
        .search-bar {
            max-width: 600px;
            margin: 2rem auto 0; /* ⬅️ tambahkan jarak atas (2rem) */
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: 2px solid #fbbf24;
            outline: none;
            font-size: 1rem;
            background-color: #ffffff;
            color: #153b9bff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            box-shadow: 0 0 0 3px rgba(251,191,36,0.4);
            transform: scale(1.02);
        }


        /* === CARD SECTION === */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
            padding: 3rem 5%;
        }

        .card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-top: 5px solid #fbbf24;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .card img {
            width: 70px;
            height: 70px;
            margin-bottom: 1rem;
            object-fit: contain;
        }

        .card h3 {
            color: #153b9bff;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .card p {
            font-size: 0.95rem;
            color: #444;
            margin-bottom: 1.2rem;
        }

        .card a {
            display: inline-block;
            background-color: #153b9bff;
            color: #fbbf24;
            border-radius: 25px;
            padding: 0.6rem 1.5rem;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .card a:hover {
            background-color: #fbbf24;
            color: #153b9bff;
            box-shadow: 0 4px 15px rgba(251,191,36,0.4);
        }

        /* === RESPONSIVE === */
        @media (max-width: 600px) {
            .fade-in-title {
                font-size: 2rem;
            }

            .fade-in-desc {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <?php 
    include '../kategoriPenyakit/includes/header.php';
    ?>

    <section class="hero-section">
        <div class="hero-content">
            <h1 class="fade-in-title">Kenali, Pahami, dan Jaga Kesehatan Anda</h1>
            <p class="fade-in-desc">
                Jelajahi berbagai kategori penyakit dalam berdasarkan sistem organ tubuh.
                Dapatkan wawasan, pencegahan, dan pemahaman lebih baik untuk hidup yang lebih sehat.
            </p>

            <div class="search-bar fade-in-search">
                <input type="text" id="searchInput" placeholder="Cari kategori penyakit...">
            </div>
        </div>
    </section>

    <section class="card-container">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="card">
                <img src="../assets/images/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
                <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                <a href="/WebDokter/kategoriPenyakit/penyakit.php?id=<?php echo urlencode($row['id']); ?>">Lihat Semua Penyakit</a>
            </div>
        <?php } ?>
    </section>

</body>
</html>
