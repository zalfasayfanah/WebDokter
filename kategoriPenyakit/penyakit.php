<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

// Ambil kategori organ
$query_kategori = "SELECT * FROM kategori_organ WHERE status = 'aktif' ORDER BY urutan";
$stmt_kategori = $db->prepare($query_kategori);
$stmt_kategori->execute();
$kategori_organ = $stmt_kategori->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dokter

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Penyakit - <?php echo $dokter['nama']; ?></title>
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
        
        .page-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .page-description {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-box {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem;
        }
        
        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .main-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem auto;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .main-icon img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }
        
        .category-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .category-btn {
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
        }
        
        .category-btn:hover {
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
            
            .page-title {
                font-size: 2rem;
            }
            
            .category-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include '../kategoriPenyakit/includes/header.php'; ?>
    
    <div class="main-container">
        <div class="sidebar">
            <div class="sidebar-item active" onclick="window.location.href='penyakit_kategori.php?id=1'">
                Mulut & Kerongkongan
            </div>
            <div class="sidebar-item" onclick="window.location.href='penyakit_kategori.php?id=2'">
                Lambung
            </div>
            <div class="sidebar-item" onclick="window.location.href='penyakit_kategori.php?id=3'">
                Usus Halus & Usus Besar
            </div>
            <div class="sidebar-item" onclick="window.location.href='penyakit_kategori.php?id=4'">
                Hati, Empedu & Pankreas
            </div>
        </div>
        
        <div class="content-area">
            <div class="page-header">
                <h1 class="page-title">KATEGORI PENYAKIT</h1>
                <p class="page-description">Eksplorasi berbagai kategori penyakit dalam berdasarkan sistem organ dan juga karakteristiknya</p>
                
                <div class="search-container">
                    <input type="text" class="search-box" placeholder="Cari kategori atau penyakit dalam">
                </div>
                
                <div class="main-icon">
                    <img src="../assets/images/cutlery 1.png" alt="Penyakit Saluran Cerna">
                </div>
                <p style="font-size: 1.2rem; font-weight: 500;">Penyakit Saluran Cerna</p>
                
                <div class="category-buttons">
                    <button class="category-btn" onclick="window.location.href='penyakit_kategori.php?id=1'">
                        Mulut & Kerongkongan
                    </button>
                    <button class="category-btn" onclick="window.location.href='penyakit_kategori.php?id=2'">
                        Lambung
                    </button>
                    <button class="category-btn" onclick="window.location.href='penyakit_kategori.php?id=3'">
                        Usus Halus & Usus Besar
                    </button>
                    <button class="category-btn" onclick="window.location.href='penyakit_kategori.php?id=4'">
                        Hati, Empedu & Pankreas
                    </button>
                </div>
            </div>
        </div>
    </div>
    
 
</body>
</html>