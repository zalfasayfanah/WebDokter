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
// PERBAIKAN: Query langsung ke tabel penyakit dengan kategori_organ_home
// kategori_id di tabel penyakit sekarang merujuk langsung ke kategori_organ_home.id
// =================================================================
$stmt = $db->prepare("
    SELECT p.*, koh.nama AS kategori_nama
    FROM penyakit p
    LEFT JOIN kategori_organ_home koh ON p.kategori_id = koh.id
    WHERE p.kategori_id = ?
    AND p.status = 'aktif'
    ORDER BY p.nama ASC
");
$stmt->execute([$kategori_home_id]);
$semua_penyakit = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        .main-container {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .content-area {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Back Button */
        .back-btn {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
        }

        .back-btn i {
            transition: transform 0.3s ease;
        }

        .back-btn:hover i {
            transform: translateX(-5px);
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 3rem;
            border-radius: 24px;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #3b82f6 0%, #1e3a8a 50%, #fbbf24 100%);
        }

        .category-icon {
            width: 140px;
            height: 140px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            overflow: hidden;
            border: 6px solid #fbbf24;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .category-icon img {
            width: 85%;
            height: 85%;
            object-fit: contain;
        }

        .category-title {
            font-size: 2.75rem;
            color: #1e3a8a;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .category-description {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 2.5rem;
            line-height: 1.7;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Search Container */
        .search-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .search-wrapper {
            position: relative;
            max-width: 650px;
            margin: 0 auto;
        }

        .search-icon {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1.25rem;
            pointer-events: none;
            z-index: 2;
        }

        .search-box {
            width: 100%;
            padding: 1.25rem 1.5rem 1.25rem 5rem;
            border-radius: 50px;
            border: 2px solid;
            border-color: #1e40af;
            font-size: 1.05rem;
            color: #1e40af;
            transition: all 0.3s ease;
            background: #f9fafb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-box:focus {
            outline: none;
            border-color: #fbbf24;
            background: white;
            font-size: 1.05rem;
            padding: 1.25rem 1.5rem 1.25rem 5rem;
            color: #1e40af;
            box-shadow: 0 4px 20px rgba(251, 191, 36, 0.2);
        }

        .clear-search {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: #e5e7eb;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #6b7280;
        }

        .clear-search:hover {
            background: #d1d5db;
            color: #374151;
        }

        .clear-search.show {
            display: flex;
        }

        /* Disease Counter */
        .disease-counter {
            font-size: 1rem;
            color: #6b7280;
            margin-top: 1.5rem;
            padding: 1rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #fef3c7 100%);
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .disease-counter strong {
            color: #1e3a8a;
            font-weight: 700;
        }

        /* Disease Grid */
        .disease-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .disease-card {
            background: white;
            border: 2px solid transparent;
            padding: 1.75rem;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .disease-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #3b82f6 0%, #fbbf24 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .disease-card:hover::before {
            transform: scaleY(1);
        }

        .disease-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #dbeafe 0%, #fef3c7 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .disease-card:hover .disease-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #fbbf24 100%);
            transform: rotate(10deg) scale(1.1);
        }

        .disease-content {
            flex: 1;
        }

        .disease-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e3a8a;
            transition: all 0.3s ease;
            line-height: 1.4;
            margin-bottom: 0.25rem;
        }

        .disease-meta {
            font-size: 0.875rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .disease-card:hover .disease-meta {
            color: #fbbf24;
        }

        .disease-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #f3f4f6;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .disease-card:hover .disease-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
        }

        .disease-arrow {
            color: #d1d5db;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .disease-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            border-color: #fbbf24;
        }

        .disease-card:hover .disease-name {
            color: #fbbf24;
        }

        .disease-card:hover .disease-arrow {
            color: #fbbf24;
            transform: translateX(5px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            color: #6b7280;
            padding: 5rem 2rem;
            font-size: 1.25rem;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .empty-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state p {
            margin-top: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .disease-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 0 1rem;
            }

            .category-title {
                font-size: 2rem;
            }

            .page-header {
                padding: 2rem 1.5rem;
            }

            .disease-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .search-box {
                font-size: 1rem;
                padding: 1rem 1rem 1rem 4.5rem;
            }

            .search-icon {
                left: 1rem;
                font-size: 1.1rem;
            }

            .back-btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.95rem;
            }
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .disease-card {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="main-container">
        <div class="content-area">

            <!-- Header Kategori -->
            <div class="page-header">
                <div class="category-icon">
                    <?php echo $home_category_icons[$kategori_home_id] ?? '🏥'; ?>
                </div>
                <h1 class="category-title"><?php echo htmlspecialchars($kategori_home['nama']); ?></h1>
                <p class="category-description"><?php echo htmlspecialchars($kategori_home['deskripsi']); ?></p>

                <!-- Search Box -->
                <div class="search-container">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-box" id="searchBox"
                            placeholder="Cari penyakit berdasarkan nama...">
                        <button class="clear-search" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Counter -->
                <div class="disease-counter">
                    <i class="fas fa-clipboard-list"></i>
                    Menampilkan <strong id="visibleCount"><?php echo count($semua_penyakit); ?></strong> dari
                    <strong><?php echo count($semua_penyakit); ?></strong> penyakit
                </div>
            </div>

            <!-- Grid Penyakit -->
            <?php if (empty($semua_penyakit)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <p><strong>Belum ada data penyakit</strong></p>
                    <p>Tidak ada penyakit yang tersedia untuk kategori ini saat ini.</p>
                </div>
            <?php else: ?>
                <div class="disease-grid" id="diseaseGrid">
                    <?php foreach ($semua_penyakit as $penyakit): ?>
                        <div class="disease-card" data-name="<?php echo strtolower(htmlspecialchars($penyakit['nama'])); ?>"
                            onclick="window.location.href='penyakit_detail.php?id=<?php echo $penyakit['id']; ?>'">
                            <div class="disease-icon">
                                <i class="fas fa-notes-medical"></i>
                            </div>
                            <div class="disease-content">
                                <div class="disease-name"><?php echo htmlspecialchars($penyakit['nama']); ?></div>
                                <div class="disease-meta">
                                    <i class="fas fa-tag"></i>
                                    <?php echo htmlspecialchars($penyakit['kategori_nama']); ?>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right disease-arrow"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const searchBox = document.getElementById('searchBox');
        const clearSearch = document.getElementById('clearSearch');
        const diseaseCards = document.querySelectorAll('.disease-card');
        const diseaseGrid = document.getElementById('diseaseGrid');
        const visibleCount = document.getElementById('visibleCount');
        const totalCount = diseaseCards.length;

        function performSearch() {
            const searchValue = searchBox.value.toLowerCase().trim();
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

            visibleCount.textContent = visible;

            if (searchValue.length > 0) {
                clearSearch.classList.add('show');
            } else {
                clearSearch.classList.remove('show');
            }

            if (visible === 0 && searchValue.length > 0) {
                if (!document.querySelector('.no-results')) {
                    const noResults = document.createElement('div');
                    noResults.className = 'empty-state no-results';
                    noResults.innerHTML = `
                        <div class="empty-icon">🔍</div>
                        <p><strong>Tidak ada hasil ditemukan</strong></p>
                        <p>Coba gunakan kata kunci yang berbeda</p>
                    `;
                    diseaseGrid.parentNode.insertBefore(noResults, diseaseGrid);
                    diseaseGrid.style.display = 'none';
                }
            } else {
                const noResults = document.querySelector('.no-results');
                if (noResults) {
                    noResults.remove();
                }
                diseaseGrid.style.display = 'grid';
            }
        }

        searchBox.addEventListener('input', performSearch);

        clearSearch.addEventListener('click', function () {
            searchBox.value = '';
            performSearch();
            searchBox.focus();
        });

        searchBox.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                searchBox.value = '';
                performSearch();
            }
        });
    </script>
</body>

</html>