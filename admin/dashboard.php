<?php
session_start();  // WAJIB!

class Database
{
    private $host = 'localhost';
    private $db_name = 'medical_website';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};port=3307;dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}


// Admin authentication
function checkAdmin()
{
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Check if user is logged in
checkAdmin();

// Database connection
$database = new Database();
$db = $database->getConnection();

// --- FUNGSI HELPER BARU UNTUK PATH GAMBAR ---
function getCleanedImagePath($filename) {
    if (empty($filename)) return '';
    // Menghapus 'assets/images/', '../assets/images/', dll. yang mungkin sudah tersimpan di database
    $cleanedFilename = str_replace(['assets/images/', '../assets/images/', '/'], '', $filename);
    // Mengembalikan path relatif dari admin.php ke folder gambar
    return 'assets/images/' . htmlspecialchars($cleanedFilename);
}
// ---------------------------------------------


// // Ambil data kategori organ (masih diperlukan jika menu kategori_organ masih ada)
// $kategoriOrgansStmt = $db->prepare("SELECT id, nama FROM kategori_organ_home WHERE status='aktif' ORDER BY nama");
// $kategoriOrgansStmt->execute();
// $kategoriOrgans = $kategoriOrgansStmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil kategori penyakit utama dari kategori_organ_home
$categoriesStmt = $db->prepare("SELECT id, nama FROM kategori_organ_home WHERE status='aktif' ORDER BY nama");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);


// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $table = $_POST['table'] ?? '';

    switch ($action) {
        case 'add':
            handleAdd($db, $table, $_POST);
            break;

        case 'edit':
            if (empty($_POST['id']) && empty($_POST['tempat_id'])) {
                die('ID untuk edit tidak ditemukan.');
            }
            handleEdit($db, $table, $_POST);
            break;

        case 'delete':
            if ($table == 'jadwal_praktek') {
                $id = $_POST['tempat_id'] ?? $_GET['tempat_id'] ?? null;
            } else {
                $id = $_POST['id'] ?? $_GET['id'] ?? null;
            }

            if ($id === null) {
                die('ID untuk delete tidak ditemukan.');
            }
            handleDelete($db, $table, $id);
            break;
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?table=" . $table);
    exit();
}

function handleEdit($db, $table, $data)
{
    $gambar = $data['gambar_lama'] ?? '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        // PERBAIKAN: Menggunakan __DIR__ yang benar
        $targetDir = __DIR__ . "/assets/images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['gambar']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
            $gambar = $fileName;
        }
    }

    switch ($table) {
        case 'jadwal_praktek':
            if (empty($data['nama_tempat']) || empty($data['alamat']) || empty($data['telp']) || empty($data['gmaps_link'])) {
                die("Harap lengkapi semua data tempat praktek sebelum menyimpan.");
            }

            $tempat_id = $data['tempat_id'] ?? null;

            if (empty($tempat_id)) {
                die("ID tempat praktek tidak ditemukan.");
            }

            $stmt = $db->prepare("UPDATE tempat_praktek SET nama_tempat=?, alamat=?, telp=?, gmaps_link=?, gambar=? WHERE id=?");
            $stmt->execute([$data['nama_tempat'], $data['alamat'], $data['telp'], $data['gmaps_link'], $gambar, $tempat_id]);

            $stmt = $db->prepare("DELETE FROM waktu_praktek WHERE tempat_id=?");
            $stmt->execute([$tempat_id]);

            if (empty($data['hari']) || empty($data['waktu'])) {
                // Diperbolehkan kosong jika tidak ada jadwal (tapi tidak disarankan)
            } else {
                foreach ($data['hari'] as $i => $hari) {
                    $waktu = $data['waktu'][$i] ?? '';
                    if (!empty($hari) && !empty($waktu)) {
                        $stmt = $db->prepare("INSERT INTO waktu_praktek (tempat_id, hari, waktu) VALUES (?, ?, ?)");
                        $stmt->execute([$tempat_id, $hari, $waktu]);
                    }
                }
            }
            break;

        case 'Organisasi':
            $stmt = $db->prepare("UPDATE organisasi SET nama_organisasi=? WHERE id=?");
            $stmt->execute([$data['nama_organisasi'], $data['id']]);
            break;

        case 'kategori_penyakit':
            $stmt = $db->prepare("UPDATE kategori_organ_home SET nama=?, deskripsi=?, gambar=?, warna=?, status=? WHERE id=?");
            $stmt->execute([
                $data['nama'],
                $data['deskripsi'],
                $gambar,
                $data['warna'],
                $data['status'],
                $data['id']
            ]);
            break;

        case 'penyakit':
            // UPDATE: kategori_id sekarang merujuk ke ID dari kategori_organ_home
            $stmt = $db->prepare("UPDATE penyakit SET kategori_id=?, nama=?, deskripsi_singkat=?, penyebab_utama=?, gejala=?, bahaya=?, cara_mencegah=?, cara_mengurangi=?, status=? WHERE id=?");
            $stmt->execute([
                $data['kategori_id'], // ID dari kategori_organ_home
                $data['nama'],
                $data['deskripsi_singkat'],
                $data['penyebab_utama'],
                $data['gejala'],
                $data['bahaya'],
                $data['cara_mencegah'],
                $data['cara_mengurangi'],
                $data['status'],
                $data['id']
            ]);
            break;
    }
}

function handleAdd($db, $table, $data)
{
    $gambar = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        // PERBAIKAN: Menggunakan __DIR__ yang benar
        $targetDir = __DIR__ . "/assets/images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['gambar']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
            $gambar = $fileName;
        }
    }

    switch ($table) {
        case 'jadwal_praktek':
            if (empty($data['nama_tempat']) || empty($data['alamat']) || empty($data['telp']) || empty($data['gmaps_link'])) {
                die("Harap lengkapi semua data tempat praktek sebelum menyimpan.");
            }

            $stmt = $db->prepare("INSERT INTO tempat_praktek (nama_tempat, alamat, telp, gmaps_link, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['nama_tempat'], $data['alamat'], $data['telp'], $data['gmaps_link'], $gambar]);

            $id_tempat = $db->lastInsertId();

            if (empty($data['hari']) || empty($data['waktu'])) {
                die("Harap isi minimal satu jadwal praktek.");
            }

            foreach ($data['hari'] as $i => $hari) {
                $waktu = $data['waktu'][$i] ?? '';
                if (!empty($hari) && !empty($waktu)) {
                    $stmt = $db->prepare("INSERT INTO waktu_praktek (tempat_id, hari, waktu) VALUES (?, ?, ?)");
                    $stmt->execute([$id_tempat, $hari, $waktu]);
                }
            }
            break;

        case 'Organisasi':
            $stmt = $db->prepare("INSERT INTO organisasi (nama_organisasi) VALUES (?)");
            $stmt->execute([$data['nama_organisasi']]);
            break;

        case 'kategori_penyakit':
            $stmt = $db->prepare("INSERT INTO kategori_organ_home (nama, deskripsi, gambar, warna, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['nama'],
                $data['deskripsi'] ?? '',
                $gambar,
                $data['warna'] ?? '#3b82f6',
                $data['status'] ?? 'aktif'
            ]);
            break;

        case 'penyakit':
            // INSERT: kategori_id sekarang merujuk ke ID dari kategori_organ
            $stmt = $db->prepare("INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['kategori_id'], // ID dari kategori_organ
                $data['nama'],
                $data['deskripsi_singkat'] ?? '',
                $data['penyebab_utama'] ?? '',
                $data['gejala'] ?? '',
                $data['bahaya'] ?? '',
                $data['cara_mencegah'] ?? '',
                $data['cara_mengurangi'] ?? '',
                $data['status'] ?? 'aktif'
            ]);
            break;
    }
}

function handleDelete($db, $table, $id)
{
    if ($table == 'jadwal_praktek') {
        // Hapus jadwal terkait dulu
        $stmt = $db->prepare("DELETE FROM waktu_praktek WHERE tempat_id = ?");
        $stmt->execute([$id]);

        // Kemudian hapus tempat praktek
        $stmt = $db->prepare("DELETE FROM tempat_praktek WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($table == 'penyakit') {
        // Untuk tabel penyakit, foreign key sudah di-set ON DELETE CASCADE
        $stmt = $db->prepare("DELETE FROM penyakit WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($table == 'kategori_penyakit') {
        // Untuk kategori_organ_home
        $stmt = $db->prepare("DELETE FROM kategori_organ_home WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        $stmt = $db->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$currentTable = $_GET['table'] ?? 'jadwal_praktek';

function getData($db, $table)
{
    switch ($table) {
        case 'jadwal_praktek':
            $stmt = $db->prepare("
                SELECT DISTINCT tp.id AS tempat_id, tp.nama_tempat, tp.alamat, tp.telp, tp.gmaps_link, tp.gambar
                FROM tempat_praktek tp
                ORDER BY tp.nama_tempat
            ");
            break;

        case 'Organisasi':
            $stmt = $db->prepare("SELECT * FROM organisasi ORDER BY nama_organisasi");
            break;

        case 'kategori_penyakit':
            $stmt = $db->prepare("SELECT * FROM kategori_organ_home ORDER BY id");
            break;

        case 'penyakit':
            // Join ke kategori_organ_home (bukan kategori_organ)
            $stmt = $db->prepare("
                SELECT p.*, 
                    koh.nama as kategori_penyakit_nama,
                    koh.id as kategori_penyakit_id
                FROM penyakit p 
                LEFT JOIN kategori_organ_home koh ON p.kategori_id = koh.id
                ORDER BY koh.nama, p.nama
            ");
            break;

        default:
            return [];
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$data = getData($db, $currentTable);

// Data pendukung untuk modal/form
$tempatsStmt = $db->prepare("SELECT id, nama_tempat, alamat FROM tempat_praktek ORDER BY nama_tempat");
$tempatsStmt->execute();
$tempats = $tempatsStmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil kategori penyakit utama dari kategori_organ_home
$categoriesStmt = $db->prepare("SELECT id, nama FROM kategori_organ_home WHERE status='aktif' ORDER BY nama");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Medical Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS yang ada tetap sama */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #2e35ecff 100%);
        }

        .sidebar .nav-link {
            color: white;
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .content-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
        }

        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 25px;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 25px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
            border-radius: 25px;
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-content {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
        }

        .table img {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table img:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        #jadwal-container {
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        #jadwal-container::-webkit-scrollbar {
            width: 8px;
        }

        #jadwal-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #jadwal-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #jadwal-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .jadwal-item {
            background: white;
            margin-bottom: 10px !important;
            padding: 10px !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
        }

        .jadwal-item:last-child {
            margin-bottom: 0 !important;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-jadwal-praktek th:nth-child(1),
        .table-jadwal-praktek td:nth-child(1) {
            width: 150px;
            min-width: 150px;
        }

        .table-jadwal-praktek th:nth-child(2),
        .table-jadwal-praktek td:nth-child(2) {
            width: 230px;
            min-width: 230px;
            max-width: 230px;
            font-size: 0.85rem;
            line-height: 1.4;
            word-wrap: break-word;
            white-space: normal;
        }

        .table-jadwal-praktek th:nth-child(3),
        .table-jadwal-praktek td:nth-child(3) {
            width: 140px;
            min-width: 140px;
        }

        .table-jadwal-praktek th:nth-child(4),
        .table-jadwal-praktek td:nth-child(4) {
            width: 120px;
            min-width: 120px;
        }

        .table-jadwal-praktek th:nth-child(5),
        .table-jadwal-praktek td:nth-child(5) {
            width: 120px;
            min-width: 120px;
            white-space: nowrap;
        }

        .table-jadwal-praktek th:nth-child(6),
        .table-jadwal-praktek td:nth-child(6) {
            width: 100px;
            min-width: 100px;
            text-align: center;
        }

        .table-jadwal-praktek th:nth-child(7),
        .table-jadwal-praktek td:nth-child(7) {
            width: 120px;
            min-width: 120px;
            text-align: center;
        }

        .table-jadwal-praktek th:nth-child(8),
        .table-jadwal-praktek td:nth-child(8) {
            width: 100px;
            min-width: 100px;
            text-align: center;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-jadwal-praktek {
            table-layout: fixed;
            width: 100%;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white"><i class="fas fa-user-md"></i> Admin Panel</h4>
                    <p class="text-white-50 mb-3">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= isset($_SESSION['admin_nama']) ? htmlspecialchars($_SESSION['admin_nama']) : 'Admin' ?>
                    </p>
                    <a href="logout.php" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>

                <nav class="nav flex-column">
                    <a href="?table=jadwal_praktek"
                        class="nav-link <?= $currentTable == 'jadwal_praktek' ? 'active' : '' ?>">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Praktek
                    </a>
                    <a href="?table=Organisasi" class="nav-link <?= $currentTable == 'Organisasi' ? 'active' : '' ?>">
                        <i class="fas fa-star me-2"></i> Organisasi
                    </a>
                    <a href="?table=kategori_penyakit"
                        class="nav-link <?= $currentTable == 'kategori_penyakit' ? 'active' : '' ?>">
                        <i class="fas fa-list me-2"></i> Kategori Penyakit
                    </a>
                    <a href="?table=penyakit" class="nav-link <?= $currentTable == 'penyakit' ? 'active' : '' ?>">
                        <i class="fas fa-virus me-2"></i> Penyakit
                    </a>
                </nav>
            </div>

            <div class="col-md-9 col-lg-10 p-4">
                <div class="content-header">
                    <h2><i class="fas fa-tachometer-alt me-3"></i>Dashboard Admin</h2>
                    <p class="mb-0">Kelola data website medis dengan mudah</p>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            Manajemen <?= ucwords(str_replace('_', ' ', $currentTable)) ?>
                        </h5>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i>Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-jadwal-praktek">
                                <thead class="table-dark">
                                    <?php renderTableHeader($currentTable); ?>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row): ?>
                                        <?php renderTableRow($currentTable, $row, $db); ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah <?= ucwords(str_replace('_', ' ', $currentTable)) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="table" value="<?= $currentTable ?>">
                        <?php renderForm($currentTable, null, $categories, $tempats); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit <?= ucwords(str_replace('_', ' ', $currentTable)) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_tempat_id" name="tempat_id">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="table" value="<?= $currentTable ?>">
                        <input type="hidden" name="id" id="edit_id">
                        <div id="editFormContent"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mengubah kode JavaScript agar bisa mengakses fungsi PHP helper untuk path gambar
        // PENTING: Anda harus memastikan fungsi getCleanedImagePath() tersedia di scope ini (di luar script tag)
        // Di sini saya mendefinisikan ulang fungsi PHP helper dalam JavaScript sebagai utility lokal
        function getAdminImagePathJS(filename) {
             if (!filename) return '';
             // Bersihkan path yang mungkin ada dan tambahkan prefix 'assets/images/'
             let cleaned = filename.replace(/assets\/images\/|\.\.\/assets\/images\/|\//g, '');
             return `assets/images/${cleaned}`;
        }


        function editData(id, tempatId, data) {
            document.getElementById('edit_id').value = id || '';
            if (document.getElementById('edit_tempat_id')) {
                document.getElementById('edit_tempat_id').value = tempatId || '';
            }

            const formContent = document.getElementById('editFormContent');
            formContent.innerHTML = generateEditForm(data);

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function deleteData(id, tempatId, name) {
            if (confirm('Apakah Anda yakin ingin menghapus "' + name + '"?')) {
                const form = document.createElement('form');
                form.method = 'POST';

                <?php if ($currentTable == 'jadwal_praktek'): ?>
                    form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="table" value="<?= $currentTable ?>">
                    <input type="hidden" name="tempat_id" value="${tempatId}">
                `;
                <?php else: ?>
                    form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="table" value="<?= $currentTable ?>">
                    <input type="hidden" name="id" value="${id}">
                `;
                <?php endif; ?>

                document.body.appendChild(form);
                form.submit();
            }
        }

        function generateEditForm(data) {
            let html = '';

            <?php if ($currentTable == 'jadwal_praktek'): ?>
                let jadwals = data.jadwal || [{
                    hari: '',
                    waktu: ''
                }];
                let jadwalFields = '';

                jadwals.forEach((j, i) => {
                    jadwalFields += `
                    <div class="jadwal-item mb-3 border p-2 rounded">
                        <label>Hari</label>
                        <input type="text" class="form-control mb-2" name="hari[]" value="${j.hari}" required>
                        <label>Jam</label>
                        <input type="text" class="form-control mb-2" name="waktu[]" value="${j.waktu}" required>
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Hapus</button>
                    </div>
                `;
                });

                // PERBAIKAN JS: Menggunakan fungsi helper untuk path gambar
                const imagePathJadwal = getAdminImagePathJS(data.gambar);

                html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Tempat</label>
                            <input type="text" class="form-control" name="nama_tempat" value="${data.nama_tempat}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" value="${data.alamat}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Telp</label>
                            <input type="text" class="form-control" name="telp" value="${data.telp}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="jadwal-container">
                            ${jadwalFields}
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="tambahJadwalBaru()">+ Tambah Jadwal</button>
                        <div class="mb-3 mt-3">
                            <label class="form-label">Link G.maps</label>
                            <input type="text" class="form-control" name="gmaps_link" value="${data.gmaps_link || ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar</label><br>
                            ${data.gambar ? `<img src="${imagePathJadwal}" alt="Preview" style="max-width: 120px; display:block; margin-bottom:10px;">` : ''}
                            <input type="file" class="form-control" name="gambar">
                            <input type="hidden" name="gambar_lama" value="${data.gambar || ''}">
                        </div>
                    </div>
                </div>
            `;
            <?php endif; ?>

            <?php if ($currentTable == 'Organisasi'): ?>
                html = `
                <div class="mb-3">
                    <label class="form-label">Nama Organisasi</label>
                    <input type="text" class="form-control" name="nama_organisasi" value="${data.nama_organisasi}" required>
                </div>
            `;
            <?php endif; ?>

            <?php if ($currentTable == 'kategori_penyakit'): ?>
                // PERBAIKAN JS: Menggunakan fungsi helper untuk path gambar
                const imagePathKategori = getAdminImagePathJS(data.gambar);
                
                html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori Penyakit</label>
                            <input type="text" class="form-control" name="nama" value="${data.nama}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <input type="color" class="form-control form-control-color" name="warna" value="${data.warna || '#3b82f6'}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="aktif" ${data.status == 'aktif' ? 'selected' : ''}>Aktif</option>
                                <option value="nonaktif" ${data.status == 'nonaktif' ? 'selected' : ''}>Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="5">${data.deskripsi || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Icon</label><br>
                            ${data.gambar ? `<img src="${imagePathKategori}" style="max-width:100px;margin-bottom:10px;border-radius:8px;">` : ''}
                            <input type="file" class="form-control" name="gambar">
                            <input type="hidden" name="gambar_lama" value="${data.gambar || ''}">
                        </div>
                    </div>
                </div>
            `;
            <?php endif; ?>

            <?php if ($currentTable == 'penyakit'): ?>
                // Ambil data kategori home/penyakit dari PHP
                const categories = <?php echo json_encode($categories); ?>;

                // Buat options untuk select
                let categoryOptions = '<option value="">-- Pilih Kategori Penyakit --</option>';

                categories.forEach(cat => {
                    const selected = (data.kategori_id == cat.id) ? 'selected' : '';
                    categoryOptions += `<option value="${cat.id}" ${selected}>${cat.nama}</option>`;
                });

                html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kategori Penyakit</label>
                            <select class="form-select" name="kategori_id" required>
                                ${categoryOptions}
                            </select>
                            <small class="text-muted">Pilih kategori penyakit dari daftar yang tersedia</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Penyakit</label>
                            <input type="text" class="form-control" name="nama" value="${data.nama}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="aktif" ${data.status == 'aktif' ? 'selected' : ''}>Aktif</option>
                                <option value="nonaktif" ${data.status == 'nonaktif' ? 'selected' : ''}>Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" name="deskripsi_singkat" rows="3">${data.deskripsi_singkat || ''}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Penyebab Utama</label>
                            <textarea class="form-control" name="penyebab_utama" rows="4">${data.penyebab_utama || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gejala</label>
                            <textarea class="form-control" name="gejala" rows="4">${data.gejala || ''}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bahaya</label>
                            <textarea class="form-control" name="bahaya" rows="4">${data.bahaya || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cara Mencegah</label>
                            <textarea class="form-control" name="cara_mencegah" rows="4">${data.cara_mencegah || ''}</textarea>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cara Mengurangi</label>
                    <textarea class="form-control" name="cara_mengurangi" rows="4">${data.cara_mengurangi || ''}</textarea>
                </div>
            `;
            <?php endif; ?>


            return html;
        }

        function tambahJadwalBaru() {
            console.log('Fungsi tambahJadwalBaru() dipanggil!');

            // Cek modal mana yang sedang terbuka
            const editModal = document.getElementById('editModal');
            const addModal = document.getElementById('addModal');

            let container;
            let isEditModal = false;

            // Tentukan container berdasarkan modal yang aktif
            if (editModal && editModal.classList.contains('show')) {
                container = editModal.querySelector('#jadwal-container');
                isEditModal = true;
                console.log('Modal EDIT sedang aktif');
            } else if (addModal && addModal.classList.contains('show')) {
                container = addModal.querySelector('#jadwal-container');
                isEditModal = false;
                console.log('Modal TAMBAH sedang aktif');
            } else {
                // Fallback ke cara lama jika tidak ada modal yang terdeteksi
                container = document.getElementById('jadwal-container');
                console.log('Menggunakan container default');
            }

            if (!container) {
                console.error('Container tidak ditemukan!');
                alert('Error: Container jadwal tidak ditemukan!');
                return;
            }

            const item = document.createElement('div');
            item.classList.add('jadwal-item', 'mb-3', 'border', 'p-2', 'rounded');
            item.style.backgroundColor = 'white';
            item.innerHTML = `
                <label class="fw-bold text-primary">Hari</label>
                <input type="text" class="form-control mb-2" name="hari[]" placeholder="Contoh: Senin, Selasa, dll" required>
                <label class="fw-bold text-primary">Jam</label>
                <input type="text" class="form-control mb-2" name="waktu[]" placeholder="Contoh: 08:00 - 12:00" required>
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="hapusJadwal(this)">
                    <i class="fas fa-trash me-1"></i> Hapus Jadwal Ini
                </button>
            `;

            container.appendChild(item);
            console.log('Jadwal baru berhasil ditambahkan!');

            // Hanya scroll jika di modal EDIT (yang punya max-height)
            if (isEditModal) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                    console.log('Container di-scroll ke:', container.scrollHeight);
                }, 100);

                // Flash effect hijau untuk jadwal yang baru ditambahkan
                item.style.border = '3px solid #28a745';
                item.style.transition = 'border 1s ease';
                setTimeout(() => {
                    item.style.border = '1px solid #dee2e6';
                }, 1500);
            }

            console.log('Total jadwal sekarang:', container.children.length);
            console.log('Modal Edit?', isEditModal);
        }

        function hapusJadwal(button) {
            if (confirm('Yakin ingin menghapus jadwal ini?')) {
                const jadwalItem = button.closest('.jadwal-item');
                if (jadwalItem) {
                    // Animasi fade out
                    jadwalItem.style.opacity = '0';
                    jadwalItem.style.transform = 'translateX(-20px)';
                    jadwalItem.style.transition = 'all 0.3s ease';

                    setTimeout(() => {
                        jadwalItem.remove();
                        console.log('Jadwal dihapus!');

                        const container = document.getElementById('jadwal-container');
                        console.log('Total jadwal sekarang:', container.children.length);
                    }, 300);
                }
            }
        }

        // Ambil data kategori saat halaman load
        const categoriesData = <?php echo json_encode($categories); ?>;
        
        // Fungsi untuk populate kategori di form add
        function populateCategoriesInAddModal() {
            const addModal = document.getElementById('addModal');
            const select = addModal.querySelector('select[name="kategori_id"]');
            
            if (select && categoriesData.length > 0) {
                // Clear existing options kecuali placeholder
                while (select.options.length > 1) {
                    select.remove(1);
                }
                
                // Tambah kategori dari data
                categoriesData.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = cat.nama;
                    select.appendChild(option);
                });
            }
        }
        
        // Jalankan saat modal add dibuka
        document.getElementById('addModal').addEventListener('show.bs.modal', function() {
            populateCategoriesInAddModal();
        });
    </script>

    <?php
    // Function to render table headers
    function renderTableHeader($table)
    {
        switch ($table) {
            case 'jadwal_praktek':
                echo '<tr>
                <th>Nama Tempat</th>
                <th>Alamat</th>
                <th>No Telp</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Link G.maps</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>';
                break;
            case 'Organisasi':
                echo '<tr><th>ID</th><th>Nama Organisasi</th><th>Aksi</th></tr>';
                break;
            case 'kategori_penyakit':
                echo '<tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>';
                break;

            case 'penyakit':
                // Label diubah menjadi Kategori Penyakit
                echo '<tr>
                <th>ID</th>
                <th>Kategori Penyakit</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>';
                break;
        }
    }

    // Function to render table rows
    function renderTableRow($table, $row, $db)
    {
        // Untuk jadwal_praktek, hanya render 1 kali per tempat_id
        static $rendered_tempat = [];

        echo '<tr>';

        switch ($table) {
            case 'jadwal_praktek':
                $tempat_id = $row['tempat_id'] ?? null;

                if (in_array($tempat_id, $rendered_tempat)) {
                    echo '</tr>';
                    return;
                }

                $rendered_tempat[] = $tempat_id;

                echo '<td>' . htmlspecialchars($row['nama_tempat'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['alamat'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['telp'] ?? '') . '</td>';

                // Ambil semua jadwal untuk tempat praktek ini
                echo '<td>';
                $jadwalQuery = $db->prepare("SELECT hari FROM waktu_praktek WHERE tempat_id=? ORDER BY id");
                $jadwalQuery->execute([$tempat_id]);
                $jadwals = $jadwalQuery->fetchAll(PDO::FETCH_ASSOC);

                if ($jadwals) {
                    $hariList = [];
                    foreach ($jadwals as $j) {
                        $hariList[] = htmlspecialchars($j['hari']);
                    }
                    echo implode('<br>', $hariList);
                } else {
                    echo '-';
                }
                echo '</td>';

                echo '<td>';
                $jamQuery = $db->prepare("SELECT waktu FROM waktu_praktek WHERE tempat_id=? ORDER BY id");
                $jamQuery->execute([$tempat_id]);
                $jams = $jamQuery->fetchAll(PDO::FETCH_ASSOC);

                if ($jams) {
                    $jamList = [];
                    foreach ($jams as $j) {
                        $jamList[] = htmlspecialchars($j['waktu']);
                    }
                    echo implode('<br>', $jamList);
                } else {
                    echo '-';
                }
                echo '</td>';

                echo '<td>';
                if (!empty($row['gmaps_link'])) {
                    echo '<a href="' . htmlspecialchars($row['gmaps_link']) . '" target="_blank">Lihat Peta</a>';
                } else {
                    echo '-';
                }
                echo '</td>';

                echo '<td>';
                if (!empty($row['gambar'])) {
                    // PERBAIKAN: Menggunakan fungsi helper
                    echo '<img src="' . getCleanedImagePath($row['gambar']) . '">';
                } else {
                    echo 'Tidak ada gambar';
                }
                echo '</td>';

                // Ambil semua jadwal untuk tombol edit
                $jadwalFullQuery = $db->prepare("SELECT hari, waktu FROM waktu_praktek WHERE tempat_id=? ORDER BY id");
                $jadwalFullQuery->execute([$tempat_id]);
                $allJadwals = $jadwalFullQuery->fetchAll(PDO::FETCH_ASSOC);

                $dataWithJadwal = $row;
                $dataWithJadwal['jadwal'] = $allJadwals;

                echo '<td class="text-nowrap">';
                echo '<button class="btn btn-sm btn-warning me-2" 
                onclick=\'editData(null, ' . $tempat_id . ', ' . json_encode($dataWithJadwal) . ')\'>
                <i class="fas fa-edit"></i>
            </button>';
                echo '<button class="btn btn-sm btn-danger" 
                onclick="deleteData(null, ' . $tempat_id . ', \'' . htmlspecialchars($row['nama_tempat']) . '\')">
                <i class="fas fa-trash"></i>
            </button>';
                echo '</td>';
                break;

            case 'Organisasi':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['nama_organisasi']) . '</td>';
                echo '<td>';
                echo '<button class="btn btn-sm btn-warning me-2" 
                onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')">
                <i class="fas fa-edit"></i>
            </button>';
                echo '<button class="btn btn-sm btn-danger" 
                onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama_organisasi']) . '\')">
                <i class="fas fa-trash"></i>
            </button>';
                echo '</td>';
                break;

            case 'kategori_penyakit':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';

                echo '<td>';
                if (!empty($row['gambar'])) {
                    // PERBAIKAN: Menggunakan fungsi helper
                    echo '<img src="' . getCleanedImagePath($row['gambar']) . '" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">';
                } else {
                    echo '-';
                }
                echo '</td>';

                echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';

                echo '<td>';
                echo '<button class="btn btn-sm btn-warning me-2" 
                onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')">
                <i class="fas fa-edit"></i>
            </button>';
                echo '<button class="btn btn-sm btn-danger" 
                onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama']) . '\')">
                <i class="fas fa-trash"></i>
            </button>';
                echo '</td>';
                break;

            case 'penyakit':
                echo '<td>' . $row['id'] . '</td>';

                // Tampilkan nama Kategori Penyakit (dari kategori_organ_home)
                echo '<td>';
                if (!empty($row['kategori_penyakit_nama'])) {
                    echo '<span class="badge bg-primary">' . htmlspecialchars($row['kategori_penyakit_nama']) . '</span>';
                } else {
                    echo '<span class="badge bg-secondary">Tidak Ada</span>';
                }
                echo '</td>';

                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['deskripsi_singkat'] ?? '', 0, 50)) . '...</td>';
                echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';

                echo '<td>';
                echo '<button class="btn btn-sm btn-warning me-2" 
                onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')">
                <i class="fas fa-edit"></i>
            </button>';
                echo '<button class="btn btn-sm btn-danger" 
                onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama']) . '\')">
                <i class="fas fa-trash"></i>
            </button>';
                echo '</td>';
                break;
        }

        echo '</tr>';
    }

    // Function to render forms
    function renderForm($table, $data = null, $categories = [], $tempats = [])
    {
        $isEdit = $data !== null;

        switch ($table) {
            case 'jadwal_praktek':
                echo '
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Tempat</label>
                            <input type="text" class="form-control" name="nama_tempat" placeholder="Masukkan nama tempat" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" placeholder="Masukkan alamat" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Telp</label>
                            <input type="text" class="form-control" name="telp" placeholder="Masukkan nomor telepon" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div id="jadwal-container">
                            <div class="jadwal-item mb-3 border p-2 rounded">
                                <label>Hari</label>
                                <input type="text" class="form-control mb-2" name="hari[]" placeholder="Contoh: Senin" required>
                                <label>Jam</label>
                                <input type="text" class="form-control mb-2" name="waktu[]" placeholder="08:00 - 12:00" required>
                                <button type="button" class="btn btn-danger btn-sm remove-jadwal">Hapus</button>
                            </div>
                        </div>
                        <button type="button" id="add-jadwal" class="btn btn-secondary btn-sm mt-2" onclick="tambahJadwalBaru()">+ Tambah Jadwal</button>

                        <div class="mb-3 mt-3">
                            <label class="form-label">Link G.maps</label>
                            <input type="url" class="form-control" name="gmaps_link" placeholder="Tempel link Google Maps" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="gambar" required>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const addModalEl = document.getElementById("addModal");
                        if (addModalEl) {
                            addModalEl.addEventListener("click", function(e) {
                                if (e.target.id === "add-jadwal") {
                                    tambahJadwalBaru();
                                }
                                if (e.target.classList.contains("remove-jadwal")) {
                                    e.target.parentElement.remove();
                                }
                            });
                        }
                    });
                </script>
                ';
                break;

            case 'Organisasi':
                echo '<div class="mb-3">
                <label class="form-label">Nama Organisasi</label>
                <input type="text" class="form-control" name="nama_organisasi" value="' . ($isEdit ? htmlspecialchars($data['nama_organisasi']) : '') . '" required>
            </div>';

                break;

            case 'kategori_penyakit':
                $imagePath = $isEdit && !empty($data['gambar']) ? getCleanedImagePath($data['gambar']) : '';

                echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori Penyakit</label>
                        <input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" placeholder="Contoh: Penyakit Saluran Cerna" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <input type="color" class="form-control form-control-color" name="warna" value="' . ($isEdit ? $data['warna'] : '#3b82f6') . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="aktif" ' . ($isEdit && $data['status'] == 'aktif' ? 'selected' : '') . '>Aktif</option>
                            <option value="nonaktif" ' . ($isEdit && $data['status'] == 'nonaktif' ? 'selected' : '') . '>Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="5" placeholder="Deskripsi singkat kategori penyakit">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Icon</label>';
                if ($isEdit && !empty($data['gambar'])) {
                    // PERBAIKAN: Menggunakan fungsi helper
                    echo '<br><img src="' . $imagePath . '" style="max-width:100px;margin-bottom:10px;border-radius:8px;">';
                }
                echo '      <input type="file" class="form-control" name="gambar"' . ($isEdit ? '' : ' required') . '>
                        <input type="hidden" name="gambar_lama" value="' . ($isEdit ? htmlspecialchars($data['gambar'] ?? '') : '') . '">
                    </div>
                </div>
            </div>';
                break;

            case 'penyakit':
                echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kategori Penyakit</label>
                        <select class="form-select" name="kategori_id" required>';
    
    echo '<option value="">-- Pilih Kategori Penyakit --</option>';
    
    // Pastikan $categories sudah terisi dengan data
    if (!empty($categories)) {
        foreach ($categories as $cat) {
            $selected = ($isEdit && isset($data['kategori_id']) && $data['kategori_id'] == $cat['id']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($cat['id']) . '" ' . $selected . '>' . htmlspecialchars($cat['nama']) . '</option>';
        }
    } else {
        echo '<option value="">Tidak ada kategori tersedia</option>';
    }
    
    echo '</select>
                    <small class="text-muted">Pilih kategori penyakit dari daftar yang tersedia</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Penyakit</label>
                    <input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="aktif" ' . ($isEdit && $data['status'] == 'aktif' ? 'selected' : '') . '>Aktif</option>
                        <option value="nonaktif" ' . ($isEdit && $data['status'] == 'nonaktif' ? 'selected' : '') . '>Non Aktif</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea class="form-control" name="deskripsi_singkat" rows="3">' . ($isEdit ? htmlspecialchars($data['deskripsi_singkat']) : '') . '</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Penyebab Utama</label>
                    <textarea class="form-control" name="penyebab_utama" rows="4">' . ($isEdit ? htmlspecialchars($data['penyebab_utama']) : '') . '</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gejala</label>
                    <textarea class="form-control" name="gejala" rows="4">' . ($isEdit ? htmlspecialchars($data['gejala']) : '') . '</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Bahaya</label>
                    <textarea class="form-control" name="bahaya" rows="4">' . ($isEdit ? htmlspecialchars($data['bahaya']) : '') . '</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cara Mencegah</label>
                    <textarea class="form-control" name="cara_mencegah" rows="4">' . ($isEdit ? htmlspecialchars($data['cara_mencegah']) : '') . '</textarea>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Cara Mengurangi</label>
            <textarea class="form-control" name="cara_mengurangi" rows="4">' . ($isEdit ? htmlspecialchars($data['cara_mengurangi']) : '') . '</textarea>
        </div>';
                break;
        }
    }
    ?>

