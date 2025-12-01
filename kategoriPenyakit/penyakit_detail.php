<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

$penyakit_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// =================================================================
// AMBIL DATA PENYAKIT DENGAN RELASI KE KATEGORI HOME
// =================================================================
$query_penyakit = "SELECT p.*, 
                            k.nama as kategori_nama, 
                            k.kategori_home_id,
                            kh.nama as kategori_home_nama
                    FROM penyakit p 
                    LEFT JOIN kategori_organ k ON p.kategori_id = k.id 
                    LEFT JOIN kategori_organ_home kh ON k.kategori_home_id = kh.id
                    WHERE p.id = :id";
$stmt_penyakit = $db->prepare($query_penyakit);
$stmt_penyakit->bindParam(':id', $penyakit_id);
$stmt_penyakit->execute();
$penyakit = $stmt_penyakit->fetch(PDO::FETCH_ASSOC);

if (!$penyakit) {
    header('Location: ../Penyakit/penyakit_home.php');
    exit;
}

// Fungsi helper untuk menentukan path gambar (perlu didefinisikan di sini jika tidak ada)
function getCleanedImagePath($filename) {
    if (empty($filename)) return '';
    // Path relatif dari folder "Penyakit" ke "assets/images" adalah "../assets/images/"
    $cleanedFilename = basename($filename);
    $cleanedFilename = str_replace(['assets/images/', '../assets/images/'], '', $cleanedFilename);
    return '../assets/images/' . htmlspecialchars($cleanedFilename);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($penyakit['nama']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .main-container {
            min-height: calc(100vh - 80px);
            background: #f5f7fa;
        }
        
        .content-area {
            max-width: 1200px;
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
        }
        
        .back-btn:hover {
            background: #4b5563;
            transform: translateX(-5px);
        }
        
        .disease-detail {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        
        .disease-title {
            font-size: 2.2rem;
            color: #1e3a8a;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        .disease-category {
            display: inline-block;
            background: #fbbf24;
            color: #1e3a8a;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        
        .disease-description {
            font-size: 1.1rem;
            color: #4b5563;
            margin-bottom: 2rem;
            line-height: 1.7;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid #3b82f6;
        }
        
        .disease-section {
            margin-bottom: 2rem;
        }
        
        .disease-section h3 {
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .disease-section h3:before {
            content: "📌";
            font-size: 1.2rem;
        }
        
        .section-highlight {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #fbbf24;
            line-height: 1.8;
            color: #374151;
        }
        
        @media (max-width: 768px) {
            .disease-title {
                font-size: 1.6rem;
            }
            
            .disease-detail {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="main-container">
        <div class="content-area">
            <button class="back-btn" onclick="window.location.href='penyakit_kategori.php?id=<?php echo $penyakit['kategori_home_id']; ?>'">
                ← Kembali ke <?php echo htmlspecialchars($penyakit['kategori_home_nama']); ?>
            </button>
            
            <div class="disease-detail">
                <h1 class="disease-title"><?php echo htmlspecialchars($penyakit['nama']); ?></h1>
                <span class="disease-category">
                    📍 <?php echo htmlspecialchars($penyakit['kategori_nama']); ?>
                </span>
                
                <div class="disease-description">
                    <?php echo nl2br(htmlspecialchars($penyakit['deskripsi_singkat'])); ?>
                </div>
                
                <?php if ($penyakit['gambar']): ?>
                <div class="disease-image-container" style="margin-bottom: 2rem; text-align: center;">
                    <img src="<?php echo getCleanedImagePath($penyakit['gambar']); ?>" 
                        alt="Ilustrasi <?php echo htmlspecialchars($penyakit['nama']); ?>" 
                        style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    ); ?>]
                </div>
                <?php endif; ?>
                <?php if ($penyakit['penyebab_utama']): ?>
                <div class="disease-section">
                    <h3>Penyebab Utama</h3>
                    <div class="section-highlight">
                        <?php echo nl2br(htmlspecialchars($penyakit['penyebab_utama'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($penyakit['gejala']): ?>
                <div class="disease-section">
                    <h3>Gejala</h3>
                    <div class="section-highlight">
                        <?php echo nl2br(htmlspecialchars($penyakit['gejala'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($penyakit['bahaya']): ?>
                <div class="disease-section">
                    <h3>Bahaya Jika Dibiarkan</h3>
                    <div class="section-highlight">
                        <?php echo nl2br(htmlspecialchars($penyakit['bahaya'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($penyakit['cara_mencegah'] || $penyakit['cara_mengurangi']): ?>
                <div class="disease-section">
                    <h3>Cara Mencegah & Mengurangi</h3>
                    <div class="section-highlight">
                        <?php if ($penyakit['cara_mencegah']): ?>
                            <strong>✅ Pencegahan:</strong><br>
                            <?php echo nl2br(htmlspecialchars($penyakit['cara_mencegah'])); ?><br><br>
                        <?php endif; ?>
                        
                        <?php if ($penyakit['cara_mengurangi']): ?>
                            <strong>💊 Pengobatan:</strong><br>
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