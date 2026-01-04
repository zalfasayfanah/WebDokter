<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

$penyakit_id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// =================================================================
// PERBAIKAN: Query langsung ke kategori_organ_home
// kategori_id di tabel penyakit sekarang langsung merujuk ke kategori_organ_home.id
// =================================================================
$query_penyakit = "SELECT p.*, 
                            kh.nama as kategori_nama, 
                            kh.id as kategori_home_id,
                            kh.deskripsi as kategori_deskripsi,
                            kh.gambar as kategori_gambar
                    FROM penyakit p 
                    LEFT JOIN kategori_organ_home kh ON p.kategori_id = kh.id
                    WHERE p.id = :id";
$stmt_penyakit = $db->prepare($query_penyakit);
$stmt_penyakit->bindParam(':id', $penyakit_id);
$stmt_penyakit->execute();
$penyakit = $stmt_penyakit->fetch(PDO::FETCH_ASSOC);

if (!$penyakit) {
    header('Location: ../Penyakit/penyakit_home.php');
    exit;
}
// ============================================
// TAMBAHAN: Parse multiple images
// ============================================
$gambar_list = [];
if (!empty($penyakit['gambar'])) {
    $gambar_list = array_filter(explode('|', $penyakit['gambar']));
}

// Fungsi helper untuk menentukan path gambar
function getCleanedImagePath($filename)
{
    if (empty($filename))
        return '';
    $cleanedFilename = basename($filename);
    $cleanedFilename = str_replace(['assets/images/', '../admin/assets/images/penyakit/'], '', $cleanedFilename);
    return '../admin/assets/images/penyakit/' . htmlspecialchars($cleanedFilename);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($penyakit['nama']); ?></title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

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

        .disease-detail {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .disease-detail::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #3b82f6 0%, #1e3a8a 50%, #fbbf24 100%);
        }

        .disease-title {
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .disease-category {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .disease-description {
            font-size: 1.15rem;
            color: #4b5563;
            margin-bottom: 3rem;
            line-height: 1.8;
            padding: 2rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #fef3c7 100%);
            border-radius: 16px;
            border-left: 5px solid #3b82f6;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .disease-image-container {
            margin-bottom: 3rem;
            text-align: center;
            padding: 2rem;
            background: #f8fafc;
            border-radius: 16px;
        }

        .disease-image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .disease-image-container img:hover {
            transform: scale(1.02);
        }

        .disease-section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: #f8fafc;
            border-radius: 16px;
            border-left: 5px solid transparent;
            transition: all 0.3s ease;
        }

        .disease-section:hover {
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .disease-section:nth-child(odd) {
            border-left-color: #3b82f6;
        }

        .disease-section:nth-child(even) {
            border-left-color: #fbbf24;
        }

        .disease-section h3 {
            color: #1e3a8a;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .disease-section h3 i {
            font-size: 1.5rem;
            color: #fbbf24;
        }

        .section-highlight {
            background: white;
            padding: 1.75rem;
            border-radius: 12px;
            line-height: 1.8;
            color: #374151;
            font-size: 1.05rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .section-highlight strong {
            color: #1e3a8a;
            display: block;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        /* Info Cards Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-top: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-card h4 {
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card h4 i {
            color: #fbbf24;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content-area {
                padding: 0 1rem;
            }

            .disease-title {
                font-size: 1.75rem;
            }

            .disease-detail {
                padding: 1.5rem;
            }

            .disease-section {
                padding: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animation */
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

        .disease-section {
            animation: fadeIn 0.6s ease forwards;
        }

        .disease-section:nth-child(1) {
            animation-delay: 0.1s;
        }

        .disease-section:nth-child(2) {
            animation-delay: 0.2s;
        }

        .disease-section:nth-child(3) {
            animation-delay: 0.3s;
        }

        .disease-section:nth-child(4) {
            animation-delay: 0.4s;
        }

        .disease-section:nth-child(5) {
            animation-delay: 0.5s;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="main-container">
        <div class="content-area">
            <button class="back-btn" onclick="window.location.href='penyakit_kategori.php?id=<?php echo $penyakit['kategori_home_id']; ?>'">
                <i class="fas fa-arrow-left"></i>
                Kembali ke <?php echo htmlspecialchars($penyakit['kategori_nama']); ?>
            </button>

            <div class="disease-detail">
                <h1 class="disease-title"><?php echo htmlspecialchars($penyakit['nama']); ?></h1>
                <span class="disease-category">
                    <i class="fas fa-tag"></i>
                    <?php echo htmlspecialchars($penyakit['kategori_nama']); ?>
                </span>

                <?php if (!empty($penyakit['deskripsi_singkat'])): ?>
                    <div class="disease-description">
                        <?php echo nl2br(htmlspecialchars($penyakit['deskripsi_singkat'])); ?>
                    </div>
                <?php endif; ?>

                <!-- ============================================ -->
                <!-- GALERI GAMBAR EDUKASI (Multiple Images) -->
                <!-- ============================================ -->

                <?php if (!empty($gambar_list) && count($gambar_list) > 0): ?>
                    <div class="disease-image-gallery">
                        <?php if (count($gambar_list) === 1): ?>
                            <!-- Jika hanya 1 gambar, tampilkan besar -->
                            <div class="single-image-container">
                                <img src="<?php echo getCleanedImagePath($gambar_list[0]); ?>"
                                    alt="Ilustrasi <?php echo htmlspecialchars($penyakit['nama']); ?>"
                                    onclick="openImageModal(this.src)"
                                    onerror="this.parentElement.innerHTML='<div class=\'image-error\'><i class=\'fas fa-image-slash\'></i><p>Gambar tidak ditemukan</p></div>'">
                            </div>
                        <?php else: ?>
                            <!-- Jika multiple images, tampilkan grid -->
                            <div class="image-grid">
                                <?php foreach ($gambar_list as $index => $gambar): ?>
                                    <div class="image-item">
                                        <img src="<?php echo getCleanedImagePath($gambar); ?>"
                                            alt="Ilustrasi <?php echo htmlspecialchars($penyakit['nama']); ?>"
                                            onclick="openImageModal(this.src)"
                                            onerror="this.parentElement.innerHTML='<div class=\'image-error\'><i class=\'fas fa-image-slash\'></i><p>Gambar tidak ditemukan</p></div>'">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Modal untuk zoom gambar -->
                <div id="imageModal" class="image-modal" onclick="closeImageModal()">
                    <span class="close-modal">&times;</span>
                    <img class="modal-content" id="modalImage">
                    <div class="modal-caption" id="modalCaption"></div>
                </div>

                <style>
                    /* Gallery Styles */
                    .disease-image-gallery {
                        margin-bottom: 3rem;
                    }

                    /* Single Image */
                    .single-image-container {
                        text-align: center;
                    }

                    .single-image-container img {
                        max-width: 100%;
                        max-height: 500px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                        border-radius: 16px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                        transition: all 0.3s ease;
                        cursor: pointer;
                    }

                    .single-image-container img:hover {
                        transform: scale(1.02);
                        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
                    }

                    /* Image Grid for Multiple Images */
                    .image-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                        gap: 0.25rem;
                    }

                    .image-item {
                        position: relative;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 400px;
                    }

                    .image-item img {
                        max-width: 100%;
                        max-height: 400px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                        border-radius: 16px;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
                    }

                    .image-item img:hover {
                        transform: scale(1.03);
                        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
                    }

                    /* Error State */
                    .image-error {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 3rem;
                        background: #fef2f2;
                        border-radius: 12px;
                        color: #dc2626;
                    }

                    .image-error i {
                        font-size: 3rem;
                        margin-bottom: 1rem;
                        opacity: 0.5;
                    }

                    .image-error p {
                        margin: 0;
                        font-size: 1rem;
                        font-weight: 500;
                    }

                    /* Modal Styles */
                    .image-modal {
                        display: none;
                        position: fixed;
                        z-index: 9999;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        overflow: auto;
                        background-color: rgba(0, 0, 0, 0.96);
                        animation: fadeIn 0.25s ease;
                        padding: 0;
                    }

                    .image-modal[style*="display: block"] {
                        display: flex !important;
                    }

                    .modal-content {
                        margin: auto;
                        display: block;
                        min-width: 80vw;
                        min-height: 80vh;
                        max-width: 98vw;
                        max-height: 98vh;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                        border-radius: 4px;
                        animation: zoomIn 0.25s ease;
                    }

                    .close-modal {
                        position: fixed;
                        top: 25px;
                        right: 40px;
                        color: #fff;
                        font-size: 50px;
                        font-weight: bold;
                        transition: 0.3s;
                        cursor: pointer;
                        z-index: 10000;
                        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
                    }

                    .close-modal:hover,
                    .close-modal:focus {
                        color: #fbbf24;
                        transform: scale(1.1);
                    }

                    .modal-caption {
                        margin: auto;
                        display: block;
                        width: 80%;
                        max-width: 700px;
                        text-align: center;
                        color: #fff;
                        padding: 15px 0;
                        font-size: 1.1rem;
                        position: fixed;
                        bottom: 30px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: rgba(0, 0, 0, 0.7);
                        padding: 12px 30px;
                        border-radius: 25px;
                        backdrop-filter: blur(10px);
                    }

                    @keyframes zoomIn {
                        from {
                            transform: scale(0.5);
                            opacity: 0;
                        }

                        to {
                            transform: scale(1);
                            opacity: 1;
                        }
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                        }

                        to {
                            opacity: 1;
                        }
                    }

                    /* Responsive */
                    @media (max-width: 768px) {
                        .image-grid {
                            grid-template-columns: 1fr;
                        }

                        .modal-content {
                            max-width: 95%;
                        }

                        .close-modal {
                            top: 10px;
                            right: 15px;
                            font-size: 30px;
                        }
                    }
                </style>

                <script>
                    // Fungsi untuk membuka modal zoom gambar
                    function openImageModal(imageSrc) {
                        const modal = document.getElementById('imageModal');
                        const modalImg = document.getElementById('modalImage');
                        const caption = document.getElementById('modalCaption');

                        modal.style.display = 'block';
                        modalImg.src = imageSrc;
                        caption.innerHTML = 'Klik di luar gambar untuk menutup';
                    }

                    // Fungsi untuk menutup modal
                    function closeImageModal() {
                        const modal = document.getElementById('imageModal');
                        modal.style.display = 'none';
                    }

                    // Tambahkan event listener untuk ESC key
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape') {
                            closeImageModal();
                        }
                    });
                </script>

                <?php if (!empty($penyakit['penyebab_utama'])): ?>
                    <div class="disease-section">
                        <h3>
                            <i class="fas fa-exclamation-circle"></i>
                            Penyebab Utama
                        </h3>
                        <div class="section-highlight">
                            <?php echo nl2br(htmlspecialchars($penyakit['penyebab_utama'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($penyakit['gejala'])): ?>
                    <div class="disease-section">
                        <h3>
                            <i class="fas fa-stethoscope"></i>
                            Gejala
                        </h3>
                        <div class="section-highlight">
                            <?php echo nl2br(htmlspecialchars($penyakit['gejala'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($penyakit['bahaya'])): ?>
                    <div class="disease-section">
                        <h3>
                            <i class="fas fa-warning"></i>
                            Bahaya Jika Dibiarkan
                        </h3>
                        <div class="section-highlight">
                            <?php echo nl2br(htmlspecialchars($penyakit['bahaya'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($penyakit['cara_mencegah']) || !empty($penyakit['cara_mengurangi'])): ?>
                    <div class="disease-section">
                        <h3>
                            <i class="fas fa-shield-alt"></i>
                            Pencegahan & Pengobatan
                        </h3>
                        <div class="info-grid">
                            <?php if (!empty($penyakit['cara_mencegah'])): ?>
                                <div class="info-card">
                                    <h4>
                                        <i class="fas fa-check-circle"></i>
                                        Cara Mencegah
                                    </h4>
                                    <div>
                                        <?php echo nl2br(htmlspecialchars($penyakit['cara_mencegah'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($penyakit['cara_mengurangi'])): ?>
                                <div class="info-card">
                                    <h4>
                                        <i class="fas fa-pills"></i>
                                        Cara Pengobatan
                                    </h4>
                                    <div>
                                        <?php echo nl2br(htmlspecialchars($penyakit['cara_mengurangi'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>