<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

$kategori_id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// Ambil data kategori
$query_kategori = "SELECT * FROM kategori_organ WHERE id = :id";
$stmt_kategori = $db->prepare($query_kategori);
$stmt_kategori->bindParam(':id', $kategori_id);
$stmt_kategori->execute();
$kategori = $stmt_kategori->fetch(PDO::FETCH_ASSOC);

if (!$kategori) {
    header('Location: penyakit.php');
    exit;
}

// Ambil semua kategori untuk sidebar
$query_all_kategori = "SELECT * FROM kategori_organ WHERE status = 'aktif' ORDER BY urutan";
$stmt_all_kategori = $db->prepare($query_all_kategori);
$stmt_all_kategori->execute();
$all_kategori = $stmt_all_kategori->fetchAll(PDO::FETCH_ASSOC);

// Ambil penyakit berdasarkan kategori
$query_penyakit = "SELECT * FROM penyakit WHERE kategori_id = :kategori_id AND status = 'aktif' ORDER BY nama";
$stmt_penyakit = $db->prepare($query_penyakit);
$stmt_penyakit->bindParam(':kategori_id', $kategori_id);
$stmt_penyakit->execute();
$penyakit_list = $stmt_penyakit->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dokter
$query_dokter = "SELECT * FROM dokter WHERE id = 1";
$stmt_dokter = $db->prepare($query_dokter);
$stmt_dokter->execute();
$dokter = $stmt_dokter->fetch(PDO::FETCH_ASSOC);

// Icon untuk setiap kategori
$category_icons = [
    1 => '<img src="../assets/images/mulut.png" alt="Mulut & Kerongkongan" style="width: 100px; height: 100px;">', // Mulut & Kerongkongan
    2 => '<img src="../assets/images/lambung.png" alt="Lambung" style="width: 120px; height: 120px;">', // Lambung
    3 => '<img src="../assets/images/usus.png" alt="Usus Halus & Usus Besar" style="width: 120px; height: 120px;">', // Usus Halus & Usus Besar
    4 => '<img src="../assets/images/hati.png" alt="Hati, Empedu & Pankreas" style="width: 90px; height: 90px;">', // Hati, Empedu & Pankreas
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $kategori['nama']; ?> - <?php echo $dokter['nama']; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .main-container {
            display: flex;
            min-height: calc(100vh - 80px);
            margin-top: 0;
        }

        .sidebar {
            width: 300px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 5rem 0 2rem 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-item {
            padding: 1rem 2rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #fbbf24;
        }

        .sidebar-item.active {
            background: rgba(251, 191, 36, 0.2);
            border-left-color: #fbbf24;
            color: #fbbf24;
        }

        .sidebar-item.active::before {
            content: "●";
            margin-right: 0.5rem;
            color: #fbbf24;
        }

        .content-area {
            flex: 1;
            margin-left: 300px;
            padding: 2rem;
            background: #f5f7fa;
            min-height: calc(100vh - 80px);
        }

        .page-header {
            background: white;
            padding: 2rem;
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
    object-fit: contain;  /* gambar pas ke lingkaran, tidak terpotong */
    padding: 10px;        /* opsional, biar ada jarak sedikit */
}



        .category-title {
            font-size: 2rem;
            color: #1e3a8a;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .disease-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .disease-btn {
            background: white;
            border: none;
            padding: 1.5rem;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
            font-weight: 500;
            color: #1e3a8a;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            text-align: left;
        }

        .disease-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: #fbbf24;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content-area {
                margin-left: 0;
            }

            .category-title {
                font-size: 1.5rem;
            }

            .disease-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="main-container">
        <div class="sidebar">
            <?php foreach ($all_kategori as $kat): ?>
                <div class="sidebar-item <?php echo $kat['id'] == $kategori_id ? 'active' : ''; ?>"
                    onclick="window.location.href='penyakit_kategori.php?id=<?php echo $kat['id']; ?>'">
                    <?php echo $kat['nama']; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="content-area">
            <div class="page-header">
                <div class="category-icon">
                    <?php echo $category_icons[$kategori_id] ?? '🏥'; ?>
                </div>
                <h1 class="category-title"><?php echo $kategori['nama']; ?></h1>

                <div class="disease-list">
                    <?php if (empty($penyakit_list)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; color: #6b7280; padding: 2rem;">
                            <p>Belum ada data penyakit untuk kategori ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($penyakit_list as $penyakit): ?>
                            <button class="disease-btn"
                                onclick="window.location.href='penyakit_detail.php?id=<?php echo $penyakit['id']; ?>'">
                                <?php echo htmlspecialchars($penyakit['nama']); ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

  
</body>

</html>