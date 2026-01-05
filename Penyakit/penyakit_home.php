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
            margin: 2rem auto 0;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            width: 100%;
            padding: 1rem 3.2rem 1rem 1.5rem;
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
            box-shadow: 0 0 0 4px rgba(251,191,36,0.4);
            transform: scale(1.05);
        }

        .search-bar .icon {
            position: absolute;
            right: 1rem;
            font-size: 1.3rem;
            color: #fbbf24;
            transition: transform 0.3s ease;
        }

        .search-bar input:focus + .icon {
            transform: rotate(20deg) scale(1.1);
        }
        /* ===== KECILKAN SEARCH DI HP ===== */
@media (max-width: 600px) {
    .search-bar {
        max-width: 90%;
        margin-top: 1.5rem;
    }

    .search-bar input {
        padding: 0.7rem 2.8rem 0.7rem 1.2rem;
        font-size: 0.7rem;
        border-width: 1.5px;
    }

    .search-bar .icon {
        font-size: 1.1rem;
        right: 0.8rem;
    }
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
       /* ===== CARD HORIZONTAL KHUSUS HP ===== */
@media (max-width: 768px) {
    .card-container {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 1rem;
        padding: 1.5rem 1rem;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .card {
        flex: 0 0 80%;
        max-width: 80%;
        scroll-snap-align: center;
    }

    .card img {
        width: 60px;
        height: 60px;
    }
}


    </style>
</head>
<body>

    <?php include '../kategoriPenyakit/includes/header.php'; ?>

    <section class="hero-section">
        <div class="hero-content">
            <h1 class="fade-in-title">Kenali, Pahami, dan Jaga Kesehatan Anda</h1>
            <p class="fade-in-desc">
                Temukan berbagai kategori penyakit dalam berdasarkan sistem organ tubuh.
                Dapatkan pemahaman mendalam untuk menjaga kesehatan Anda dengan lebih baik.
            </p>

            <div class="search-bar fade-in-search">
                <input type="text" id="searchInput" placeholder="Cari kategori penyakit...">
                <span class="icon">🔍</span>
            </div>
        </div>
    </section>

    <section class="card-container" id="cardContainer">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="card" data-name="<?php echo strtolower(htmlspecialchars($row['nama'])); ?>">
                <?php
                // 1. Ambil nama file saja (bersihkan jika ada path nyasar dari database)
                $nama_file = basename($row['gambar']);
                
                // 2. Tentukan lokasi folder admin/assets/images
                // Gunakan "../" karena file ini berada di dalam folder, jadi harus keluar dulu baru masuk ke admin
                $path_gambar = "../admin/assets/images/" . $nama_file;
                
                // 3. (Opsional) Cek jika gambar kosong, pakai placeholder default
                if (empty($nama_file)) {
                    // Ganti link ini dengan gambar default Anda jika ada
                    $path_gambar = "https://via.placeholder.com/150?text=No+Image"; 
                }
                ?>
                
                <img src="<?php echo htmlspecialchars($path_gambar); ?>" 
                     alt="<?php echo htmlspecialchars($row['nama']); ?>"
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=Gambar+Rusak';"> <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
                <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                
                <a href="penyakit_kategori.php?id=<?php echo urlencode($row['id']); ?>">Lihat Semua Penyakit</a>
            </div>
        <?php } ?>
    </section>

    <script>
        // === INTERAKTIF PENCARIAN REAL-TIME ===
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('.card');

        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                card.style.display = name.includes(searchValue) ? 'block' : 'none';
            });
        });
    </script>

</body>
</html>