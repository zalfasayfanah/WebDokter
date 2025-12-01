<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

// =================================================================
// Ambil kategori_home_id dari URL
// =================================================================
$kategori_home_id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// =================================================================
// AMBIL DATA KATEGORI HOME (Penyakit Saluran Cerna, dll)
// =================================================================
$query_kategori_home = "SELECT * FROM kategori_organ_home WHERE id = :id";
$stmt_kategori_home = $db->prepare($query_kategori_home);
$stmt_kategori_home->bindParam(':id', $kategori_home_id);
$stmt_kategori_home->execute();
$kategori_home = $stmt_kategori_home->fetch(PDO::FETCH_ASSOC);

if (!$kategori_home) {
    header('Location: ../Penyakit/penyakit_home.php');
    exit;
}

// =================================================================
// AMBIL SEMUA PENYAKIT LANGSUNG TANPA PENGELOMPOKAN ORGAN
// Hanya diurutkan berdasarkan nama penyakit (alfabetis)
// =================================================================
$query_penyakit = "SELECT p.*, k.nama as kategori_organ_nama 
                   FROM penyakit p
                   INNER JOIN kategori_organ k ON p.kategori_id = k.id
                   WHERE k.kategori_home_id = :kategori_home_id 
                   AND p.status = 'aktif' 
                   ORDER BY p.nama ASC";
$stmt_penyakit = $db->prepare($query_penyakit);
$stmt_penyakit->bindParam(':kategori_home_id', $kategori_home_id);
$stmt_penyakit->execute();
$semua_penyakit = $stmt_penyakit->fetchAll(PDO::FETCH_ASSOC);

// Icon untuk kategori home
$home_category_icons = [
    1 => '<img src="../assets/images/cutlery 1.png" alt="Saluran Cerna" style="width: 100px; height: 100px;">',
    2 => '<img src="../assets/images/kidney 1.png" alt="Ginjal" style="width: 100px; height: 100px;">',
    3 => '<img src="../assets/images/lung 1.png" alt="Pernapasan" style="width: 100px; height: 100px;">',
    4 => '<img src="../assets/images/backteri1.png" alt="Infeksi" style="width: 100px; height: 100px;">',
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($kategori_home['nama']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* =================================================================
           STYLE BARU: Full width, no sidebar, no grouping
        ================================================================= */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .main-container {
            min-height: calc(100vh - 80px);
            background: #f5f7fa;
        }

        .content-area {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .back-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .back-btn:hover {
            background: #4b5563;
            transform: translateX(-5px);
        }

        .page-header {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .category-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #3b82f6, #1e3a8a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            overflow: hidden;
            border: 4px solid #fbbf24;
        }

        .category-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .category-title {
            font-size: 2.5rem;
            color: #1e3a8a;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .category-description {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Search Box */
        .search-container {
            margin-bottom: 2rem;
        }

        .search-box {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            border-radius: 25px;
            border: 2px solid #e5e7eb;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-box:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
        }

        /* =================================================================
           GRID PENYAKIT TANPA PENGELOMPOKAN
        ================================================================= */
        .disease-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .disease-card {
            background: white;
            border: 2px solid #e5e7eb;
            padding: 1.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .disease-card:before {
            content: "📋";
            font-size: 2rem;
            flex-shrink: 0;
        }

        .disease-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            border-color: #fbbf24;
        }

        .disease-name {
            font-size: 1.05rem;
            font-weight: 600;
            color: #1e3a8a;
            transition: color 0.3s;
        }

        .disease-card:hover .disease-name {
            color: white;
        }

        .empty-state {
            text-align: center;
            color: #6b7280;
            padding: 4rem 2rem;
            font-size: 1.2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-state:before {
            content: "📭";
            display: block;
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Counter */
        .disease-counter {
            font-size: 0.95rem;
            color: #6b7280;
            margin-top: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .category-title {
                font-size: 1.8rem;
            }

            .disease-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .content-area {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="main-container">
        <div class="content-area">
            <!-- Tombol Kembali -->
            <button class="back-btn" onclick="window.location.href='../Penyakit/penyakit_home.php'">
                ← Kembali ke Beranda
            </button>

            <!-- Header Kategori -->
            <div class="page-header">
                <div class="category-icon">
                    <?php echo $home_category_icons[$kategori_home_id] ?? '🏥'; ?>
                </div>
                <h1 class="category-title"><?php echo htmlspecialchars($kategori_home['nama']); ?></h1>
                <p class="category-description"><?php echo htmlspecialchars($kategori_home['deskripsi']); ?></p>

                <!-- Search Box -->
                <div class="search-container">
                    <input type="text" 
                           class="search-box" 
                           id="searchBox" 
                           placeholder="🔍 Cari penyakit...">
                </div>

                <!-- Counter -->
                <p class="disease-counter">
                    Menampilkan <strong id="visibleCount"><?php echo count($semua_penyakit); ?></strong> dari 
                    <strong><?php echo count($semua_penyakit); ?></strong> penyakit
                </p>
            </div>

            <!-- =================================================================
                 TAMPILKAN SEMUA PENYAKIT LANGSUNG TANPA PENGELOMPOKAN
            ================================================================= -->
            <?php if (empty($semua_penyakit)): ?>
                <div class="empty-state">
                    <p>Belum ada data penyakit untuk kategori ini.</p>
                </div>
            <?php else: ?>
                <div class="disease-grid" id="diseaseGrid">
                    <?php foreach ($semua_penyakit as $penyakit): ?>
                        <div class="disease-card" 
                             data-name="<?php echo strtolower(htmlspecialchars($penyakit['nama'])); ?>"
                             onclick="window.location.href='penyakit_detail.php?id=<?php echo $penyakit['id']; ?>'">
                            <span class="disease-name"><?php echo htmlspecialchars($penyakit['nama']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // =================================================================
        // FITUR PENCARIAN REAL-TIME
        // =================================================================
        const searchBox = document.getElementById('searchBox');
        const diseaseCards = document.querySelectorAll('.disease-card');
        const visibleCount = document.getElementById('visibleCount');
        const totalCount = diseaseCards.length;

        searchBox.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase().trim();
            let visible = 0;

            diseaseCards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(searchValue)) {
                    card.style.display = 'flex';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update counter
            visibleCount.textContent = visible;
        });
    </script>
</body>
</html>
