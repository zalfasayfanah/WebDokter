<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

$penyakit_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Ambil data penyakit dengan kategori
$query_penyakit = "SELECT p.*, k.nama as kategori_nama FROM penyakit p 
                   LEFT JOIN kategori_organ k ON p.kategori_id = k.id 
                   WHERE p.id = :id";
$stmt_penyakit = $db->prepare($query_penyakit);
$stmt_penyakit->bindParam(':id', $penyakit_id);
$stmt_penyakit->execute();
$penyakit = $stmt_penyakit->fetch(PDO::FETCH_ASSOC);

if (!$penyakit) {
    header('Location: penyakit.php');
    exit;
}

// Ambil semua kategori untuk sidebar
$query_all_kategori = "SELECT * FROM kategori_organ WHERE status = 'aktif' ORDER BY urutan";
$stmt_all_kategori = $db->prepare($query_all_kategori);
$stmt_all_kategori->execute();
$all_kategori = $stmt_all_kategori->fetchAll(PDO::FETCH_ASSOC);

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
    <title><?php echo $penyakit['nama']; ?> - <?php echo $dokter['nama']; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .main-container {
            display: flex;
            min-height: calc(100vh - 80px);
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
        }
        
        .back-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #4b5563;
        }
        
        .disease-detail {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        
        .disease-title {
            font-size: 2rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .disease-description {
            font-size: 1.1rem;
            color: #4b5563;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .disease-section {
            margin-bottom: 2rem;
        }
        
        .disease-section h3 {
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .disease-section ul {
            padding-left: 1.5rem;
            line-height: 1.8;
            color: #4b5563;
        }
        
        .disease-section li {
            margin-bottom: 0.5rem;
        }
        
        .section-highlight {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #fbbf24;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .content-area {
                margin-left: 0;
            }
            
            .disease-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <div class="sidebar">
            <?php foreach ($all_kategori as $kat): ?>
                <div class="sidebar-item <?php echo $kat['id'] == $penyakit['kategori_id'] ? 'active' : ''; ?>" 
                     onclick="window.location.href='penyakit_kategori.php?id=<?php echo $kat['id']; ?>'">
                    <?php echo $kat['nama']; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="content-area">
            <button class="back-btn" onclick="window.location.href='penyakit_kategori.php?id=<?php echo $penyakit['kategori_id']; ?>'">
                ← Kembali
            </button>
            
            <div class="disease-detail">
                <h1 class="disease-title"><?php echo htmlspecialchars($penyakit['nama']); ?></h1>
                
                <div class="disease-description">
                    <?php echo nl2br(htmlspecialchars($penyakit['deskripsi_singkat'])); ?>
                </div>
                
                <?php if ($penyakit['penyebab_utama']): ?>
                <div class="disease-section">
                    <h3>Penyebab utama:</h3>
                    <div class="section-highlight">
                        <?php echo nl2br(htmlspecialchars($penyakit['penyebab_utama'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($penyakit['gejala']): ?>
                <div class="disease-section">
                    <h3>Gejala:</h3>
                    <div class="section-highlight">
                        <?php echo nl2br(htmlspecialchars($penyakit['gejala'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($penyakit['bahaya']): ?>
                <div class="disease-section">
                    <h3>Bahaya jika dibiarkan:</h3>
                    <div class="section-highlight">
                        <?php echo nl2br(htmlspecialchars($penyakit['bahaya'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($penyakit['cara_mencegah'] || $penyakit['cara_mengurangi']): ?>
                <div class="disease-section">
                    <h3>Cara mencegah & mengurangi:</h3>
                    <div class="section-highlight">
                        <?php if ($penyakit['cara_mencegah']): ?>
                            <strong>Pencegahan:</strong><br>
                            <?php echo nl2br(htmlspecialchars($penyakit['cara_mencegah'])); ?><br><br>
                        <?php endif; ?>
                        
                        <?php if ($penyakit['cara_mengurangi']): ?>
                            <strong>Pengobatan:</strong><br>
                            <?php echo nl2br(htmlspecialchars($penyakit['cara_mengurangi'])); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
   
</body>
</html>