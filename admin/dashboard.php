<?php
session_start();  // WAJIB!

class Database
{
    private $host = 'localhost';
    private $db_name = 'medical_website2';
    private $username = 'root';
    private $password = 'password'; // password kosong untuk XAMPP default
    private $port = '3306';

    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);

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
        // Jika belum login, redirect ke login page (sesuaikan nama file login Anda)
        // header("Location: login.php"); 
        // exit();
        
        // Untuk demo/testing sementara saya disable redirect jika session belum ada
        // Hapus komentar di atas jika sudah production
    }
}

// Check if user is logged in
checkAdmin();

// Database connection
$database = new Database();
$db = $database->getConnection();

// --- FUNGSI HELPER BARU UNTUK PATH GAMBAR ---
function getCleanedImagePath($filename)
{
    if (empty($filename)) return '';
    // Menggunakan basename() untuk mengambil nama file saja, membuang path folder yang mungkin ikut tersimpan
    $cleanName = basename($filename);
    
    // Mengembalikan path relatif yang benar agar browser bisa menemukannya
    return 'assets/images/' . htmlspecialchars($cleanName);
}
// ---------------------------------------------

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

            if (!empty($data['hari']) && !empty($data['waktu'])) {
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
            // Ambil gambar lama dari database
            $stmtOld = $db->prepare("SELECT gambar FROM penyakit WHERE id=?");
            $stmtOld->execute([$data['id']]);
            $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);
            $oldImages = !empty($oldData['gambar']) ? explode('|', $oldData['gambar']) : [];

            // Hapus gambar yang dipilih untuk dihapus
            if (isset($data['hapus_gambar']) && is_array($data['hapus_gambar'])) {
                foreach ($data['hapus_gambar'] as $imgToDelete) {
                    $filePath = __DIR__ . "/assets/images/penyakit/" . $imgToDelete;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $oldImages = array_diff($oldImages, [$imgToDelete]);
                }
            }

            // Upload gambar baru
            $newImages = [];
            if (isset($_FILES['gambar_penyakit']) && !empty($_FILES['gambar_penyakit']['name'][0])) {
                $targetDir = __DIR__ . "/assets/images/penyakit/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                foreach ($_FILES['gambar_penyakit']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['gambar_penyakit']['error'][$key] != 0) continue;
                    if ($_FILES['gambar_penyakit']['size'][$key] > 5242880) continue;

                    $ext = pathinfo($_FILES['gambar_penyakit']['name'][$key], PATHINFO_EXTENSION);
                    $fileName = time() . "_" . ($key + 1) . "_" . uniqid() . "." . $ext;
                    $targetFilePath = $targetDir . $fileName;

                    if (move_uploaded_file($tmp_name, $targetFilePath)) {
                        $newImages[] = $fileName;
                    }

                    if ((count($oldImages) + count($newImages)) >= 3) break;
                }
            }

            $allImages = array_merge($oldImages, $newImages);
            $gambarString = implode('|', $allImages);

            $stmt = $db->prepare("UPDATE penyakit SET kategori_id=?, nama=?, deskripsi_singkat=?, penyebab_utama=?, gejala=?, bahaya=?, cara_mencegah=?, cara_mengurangi=?, gambar=?, status=? WHERE id=?");
            $stmt->execute([
                $data['kategori_id'],
                $data['nama'],
                $data['deskripsi_singkat'],
                $data['penyebab_utama'],
                $data['gejala'],
                $data['bahaya'],
                $data['cara_mencegah'],
                $data['cara_mengurangi'],
                $gambarString,
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
            $uploadedImages = [];
            if (isset($_FILES['gambar_penyakit']) && !empty($_FILES['gambar_penyakit']['name'][0])) {
                $targetDir = __DIR__ . "/assets/images/penyakit/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                foreach ($_FILES['gambar_penyakit']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['gambar_penyakit']['error'][$key] != 0) continue;
                    if ($_FILES['gambar_penyakit']['size'][$key] > 5242880) continue;

                    $ext = pathinfo($_FILES['gambar_penyakit']['name'][$key], PATHINFO_EXTENSION);
                    $fileName = time() . "_" . ($key + 1) . "_" . uniqid() . "." . $ext;
                    $targetFilePath = $targetDir . $fileName;

                    if (move_uploaded_file($tmp_name, $targetFilePath)) {
                        $uploadedImages[] = $fileName;
                    }

                    if (count($uploadedImages) >= 3) break;
                }
            }

            $gambarString = implode('|', $uploadedImages);

            $stmt = $db->prepare("INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi, gambar, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['kategori_id'],
                $data['nama'],
                $data['deskripsi_singkat'] ?? '',
                $data['penyebab_utama'] ?? '',
                $data['gejala'] ?? '',
                $data['bahaya'] ?? '',
                $data['cara_mencegah'] ?? '',
                $data['cara_mengurangi'] ?? '',
                $gambarString,
                $data['status'] ?? 'aktif'
            ]);
            break;
    }
}

function handleDelete($db, $table, $id)
{
    if ($table == 'jadwal_praktek') {
        $stmt = $db->prepare("DELETE FROM waktu_praktek WHERE tempat_id = ?");
        $stmt->execute([$id]);
        $stmt = $db->prepare("DELETE FROM tempat_praktek WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($table == 'penyakit') {
        $stmt = $db->prepare("DELETE FROM penyakit WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($table == 'kategori_penyakit') {
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
$tempatsStmt = $db->prepare("SELECT id, nama_tempat, alamat FROM tempat_praktek ORDER BY nama_tempat");
$tempatsStmt->execute();
$tempats = $tempatsStmt->fetchAll(PDO::FETCH_ASSOC);
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
        /* CSS STYLE */
        .sidebar { min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #2e35ecff 100%); }
        .sidebar .nav-link { color: white; border-radius: 10px; margin: 5px 0; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255, 255, 255, 0.2); color: white; }
        .content-header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px; }
        .btn-success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; border-radius: 25px; }
        .btn-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; border-radius: 25px; }
        .btn-danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none; border-radius: 25px; }
        .table { border-radius: 15px; overflow: hidden; }
        .modal-content { border-radius: 20px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); }
        .modal-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; }
        .form-control, .form-select { border-radius: 10px; }
        
        .table img {
            width: 100px; height: 80px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .table img:hover { transform: scale(1.05); transition: transform 0.3s ease; cursor: pointer; }
        
        #jadwal-container { max-height: 250px; overflow-y: auto; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 10px; }
        .jadwal-item { background: white; margin-bottom: 10px !important; padding: 10px !important; border: 1px solid #dee2e6 !important; border-radius: 8px !important; }
        
        /* Table widths adjustment */
        .table-responsive { overflow-x: auto; }
        .table-jadwal-praktek { width: 100%; }
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
                    <a href="?table=jadwal_praktek" class="nav-link <?= $currentTable == 'jadwal_praktek' ? 'active' : '' ?>">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Praktek
                    </a>
                    <a href="?table=Organisasi" class="nav-link <?= $currentTable == 'Organisasi' ? 'active' : '' ?>">
                        <i class="fas fa-star me-2"></i> Organisasi
                    </a>
                    <a href="?table=kategori_penyakit" class="nav-link <?= $currentTable == 'kategori_penyakit' ? 'active' : '' ?>">
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
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchTableInput" placeholder="Cari data (nama, deskripsi, dll)..." onkeyup="searchTable()">
                            </div>
                            <small class="text-muted">Ketik untuk menyaring data pada tabel di bawah.</small>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-jadwal-praktek" id="mainTable">
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
        // FUNGSI PENCARIAN GLOBAL
        function searchTable() {
            var input = document.getElementById("searchTableInput");
            var filter = input.value.toLowerCase();
            var table = document.getElementById("mainTable");
            var tr = table.getElementsByTagName("tr");

            // Loop semua baris tabel, sembunyikan yang tidak cocok dengan query pencarian
            for (var i = 1; i < tr.length; i++) {
                var rowContent = tr[i].textContent || tr[i].innerText;
                if (rowContent.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        function getAdminImagePathJS(filename) {
            if (!filename) return '';
            // Ambil nama file saja (mirip basename di PHP)
            let cleanName = filename.split('\\').pop().split('/').pop();
            return `assets/images/${cleanName}`;
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
                    form.innerHTML = `<input type="hidden" name="action" value="delete"><input type="hidden" name="table" value="<?= $currentTable ?>"><input type="hidden" name="tempat_id" value="${tempatId}">`;
                <?php else: ?>
                    form.innerHTML = `<input type="hidden" name="action" value="delete"><input type="hidden" name="table" value="<?= $currentTable ?>"><input type="hidden" name="id" value="${id}">`;
                <?php endif; ?>
                document.body.appendChild(form);
                form.submit();
            }
        }

        // --- Generate Edit Form (Include JS Helper) ---
        function generateEditForm(data) {
            let html = '';
            
            // JADWAL PRAKTEK
            <?php if ($currentTable == 'jadwal_praktek'): ?>
                let jadwals = data.jadwal || [{ hari: '', waktu: '' }];
                let jadwalFields = '';
                jadwals.forEach((j, i) => {
                    jadwalFields += `<div class="jadwal-item mb-3 border p-2 rounded"><label>Hari</label><input type="text" class="form-control mb-2" name="hari[]" value="${j.hari}" required><label>Jam</label><input type="text" class="form-control mb-2" name="waktu[]" value="${j.waktu}" required><button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Hapus</button></div>`;
                });
                const imagePathJadwal = getAdminImagePathJS(data.gambar);
                html = `<div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Nama Tempat</label><input type="text" class="form-control" name="nama_tempat" value="${data.nama_tempat}" required></div><div class="mb-3"><label class="form-label">Alamat</label><input type="text" class="form-control" name="alamat" value="${data.alamat}" required></div><div class="mb-3"><label class="form-label">No Telp</label><input type="text" class="form-control" name="telp" value="${data.telp}" required></div></div><div class="col-md-6"><div id="jadwal-container">${jadwalFields}</div><button type="button" class="btn btn-secondary btn-sm mt-2" onclick="tambahJadwalBaru()">+ Tambah Jadwal</button><div class="mb-3 mt-3"><label class="form-label">Link G.maps</label><input type="text" class="form-control" name="gmaps_link" value="${data.gmaps_link || ''}"></div><div class="mb-3"><label class="form-label">Gambar</label><br>${data.gambar ? `<img src="${imagePathJadwal}" alt="Preview" style="max-width: 120px; display:block; margin-bottom:10px;">` : ''}<input type="file" class="form-control" name="gambar"><input type="hidden" name="gambar_lama" value="${data.gambar || ''}"></div></div></div>`;
            <?php endif; ?>

            // ORGANISASI
            <?php if ($currentTable == 'Organisasi'): ?>
                html = `<div class="mb-3"><label class="form-label">Nama Organisasi</label><input type="text" class="form-control" name="nama_organisasi" value="${data.nama_organisasi}" required></div>`;
            <?php endif; ?>

            // KATEGORI PENYAKIT
            <?php if ($currentTable == 'kategori_penyakit'): ?>
                const imagePathKategori = getAdminImagePathJS(data.gambar);
                html = `<div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Nama Kategori Penyakit</label><input type="text" class="form-control" name="nama" value="${data.nama}" required></div><div class="mb-3"><label class="form-label">Warna</label><input type="color" class="form-control form-control-color" name="warna" value="${data.warna || '#3b82f6'}"></div><div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="aktif" ${data.status == 'aktif' ? 'selected' : ''}>Aktif</option><option value="nonaktif" ${data.status == 'nonaktif' ? 'selected' : ''}>Non Aktif</option></select></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="5">${data.deskripsi || ''}</textarea></div><div class="mb-3"><label class="form-label">Gambar Icon</label><br>${data.gambar ? `<img src="${imagePathKategori}" style="max-width:100px;margin-bottom:10px;border-radius:8px;">` : ''}<input type="file" class="form-control" name="gambar"><input type="hidden" name="gambar_lama" value="${data.gambar || ''}"></div></div></div>`;
            <?php endif; ?>

            // PENYAKIT
            <?php if ($currentTable == 'penyakit'): ?>
                const categories = <?php echo json_encode($categories); ?>;
                let categoryOptions = '<option value="">-- Pilih Kategori Penyakit --</option>';
                categories.forEach(cat => {
                    const selected = (data.kategori_id == cat.id) ? 'selected' : '';
                    categoryOptions += `<option value="${cat.id}" ${selected}>${cat.nama}</option>`;
                });
                html = `<div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Kategori Penyakit</label><select class="form-select" name="kategori_id" required>${categoryOptions}</select></div><div class="mb-3"><label class="form-label">Nama Penyakit</label><input type="text" class="form-control" name="nama" value="${data.nama}" required></div><div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="aktif" ${data.status == 'aktif' ? 'selected' : ''}>Aktif</option><option value="nonaktif" ${data.status == 'nonaktif' ? 'selected' : ''}>Non Aktif</option></select></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Deskripsi Singkat</label><textarea class="form-control" name="deskripsi_singkat" rows="3">${data.deskripsi_singkat || ''}</textarea></div></div></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Penyebab Utama</label><textarea class="form-control" name="penyebab_utama" rows="4">${data.penyebab_utama || ''}</textarea></div><div class="mb-3"><label class="form-label">Gejala</label><textarea class="form-control" name="gejala" rows="4">${data.gejala || ''}</textarea></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Bahaya</label><textarea class="form-control" name="bahaya" rows="4">${data.bahaya || ''}</textarea></div><div class="mb-3"><label class="form-label">Cara Mencegah</label><textarea class="form-control" name="cara_mencegah" rows="4">${data.cara_mencegah || ''}</textarea></div></div></div><div class="mb-3"><label class="form-label">Cara Mengurangi</label><textarea class="form-control" name="cara_mengurangi" rows="4">${data.cara_mengurangi || ''}</textarea></div><div class="mb-3"><label class="form-label">Gambar yang Sudah Ada</label>${generateExistingImagesHTML(data.gambar)}</div><div class="mb-3"><label class="form-label">Tambah Gambar Baru</label><input type="file" class="form-control" name="gambar_penyakit[]" multiple accept="image/*" onchange="previewMultipleImages(this)"></div>`;
            <?php endif; ?>

            return html;
        }

        // Functions lain (copy paste dari original)
        function generateExistingImagesHTML(gambarString) {
            if (!gambarString) return '<p class="text-muted">Belum ada gambar</p>';
            const images = gambarString.split('|').filter(img => img.trim() !== '');
            if (images.length === 0) return '<p class="text-muted">Belum ada gambar</p>';
            let html = '<div class="row g-3">';
            images.forEach((img, index) => {
                const cleanImg = img.trim();
                html += `<div class="col-md-4"><div class="card"><img src="assets/images/penyakit/${cleanImg}" class="card-img-top" style="height:150px;object-fit:cover;" onerror="this.parentElement.innerHTML='<div class=\\'text-center p-3\\'><small>Gambar tidak ditemukan</small></div>'"><div class="card-body text-center p-2"><label class="text-danger" style="cursor:pointer;"><input type="checkbox" name="hapus_gambar[]" value="${cleanImg}" class="me-1"> Hapus</label></div></div></div>`;
            });
            html += '</div>';
            return html;
        }

        function tambahJadwalBaru() {
            // Logic tambah jadwal sama seperti sebelumnya
            const container = document.getElementById('editModal').classList.contains('show') ? document.querySelector('#editModal #jadwal-container') : document.getElementById('jadwal-container');
            if(!container) return;
            const item = document.createElement('div');
            item.className = 'jadwal-item mb-3 border p-2 rounded';
            item.innerHTML = `<label>Hari</label><input type="text" class="form-control mb-2" name="hari[]" required><label>Jam</label><input type="text" class="form-control mb-2" name="waktu[]" required><button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Hapus</button>`;
            container.appendChild(item);
        }
        
        function previewMultipleImages(input) {
           // Logic preview sama seperti sebelumnya
        }
    </script>

<?php
// ==========================================
// RENDER TABLE HEADER (MODIFIKASI: HAPUS ID)
// ==========================================
function renderTableHeader($table)
{
    switch ($table) {
        case 'jadwal_praktek':
            echo '<tr><th>Nama Tempat</th><th>Alamat</th><th>No Telp</th><th>Hari</th><th>Jam</th><th>Link G.maps</th><th>Gambar</th><th>Aksi</th></tr>';
            break;
        case 'Organisasi':
            // ID Dihapus
            echo '<tr><th>Nama Organisasi</th><th>Aksi</th></tr>';
            break;
        case 'kategori_penyakit':
            // ID Dihapus
            echo '<tr><th>Nama Kategori</th><th>Deskripsi</th><th>Gambar</th><th>Status</th><th>Aksi</th></tr>';
            break;
        case 'penyakit':
            echo '<tr><th>Kategori Penyakit</th><th>Nama</th><th>Deskripsi</th><th>Gambar</th><th>Status</th><th>Aksi</th></tr>';
            break;
    }
}

// ==========================================
// RENDER TABLE ROW (MODIFIKASI: HAPUS ID & FIX GAMBAR)
// ==========================================
function renderTableRow($table, $row, $db)
{
    static $rendered_tempat = [];
    echo '<tr>';

    switch ($table) {
        case 'jadwal_praktek':
            $tempat_id = $row['tempat_id'] ?? null;
            if (in_array($tempat_id, $rendered_tempat)) { echo '</tr>'; return; }
            $rendered_tempat[] = $tempat_id;
            echo '<td>' . htmlspecialchars($row['nama_tempat'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['alamat'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['telp'] ?? '') . '</td>';
            // Logic jadwal sama...
            echo '<td>';
            $jadwalQuery = $db->prepare("SELECT hari FROM waktu_praktek WHERE tempat_id=? ORDER BY id");
            $jadwalQuery->execute([$tempat_id]);
            $jadwals = $jadwalQuery->fetchAll(PDO::FETCH_ASSOC);
            if ($jadwals) { $h = []; foreach($jadwals as $j) $h[] = htmlspecialchars($j['hari']); echo implode('<br>', $h); } else echo '-';
            echo '</td><td>';
            $jamQuery = $db->prepare("SELECT waktu FROM waktu_praktek WHERE tempat_id=? ORDER BY id");
            $jamQuery->execute([$tempat_id]);
            $jams = $jamQuery->fetchAll(PDO::FETCH_ASSOC);
            if ($jams) { $jm = []; foreach($jams as $j) $jm[] = htmlspecialchars($j['waktu']); echo implode('<br>', $jm); } else echo '-';
            echo '</td>';
            echo '<td>' . (!empty($row['gmaps_link']) ? '<a href="' . htmlspecialchars($row['gmaps_link']) . '" target="_blank">Peta</a>' : '-') . '</td>';
            
            // FIX GAMBAR JADWAL
            echo '<td>';
            if (!empty($row['gambar'])) {
                echo '<img src="' . getCleanedImagePath($row['gambar']) . '">';
            } else {
                echo 'Tidak ada gambar';
            }
            echo '</td>';
            
            // Tombol edit/delete...
             $jadwalFullQuery = $db->prepare("SELECT hari, waktu FROM waktu_praktek WHERE tempat_id=? ORDER BY id");
             $jadwalFullQuery->execute([$tempat_id]);
             $allJadwals = $jadwalFullQuery->fetchAll(PDO::FETCH_ASSOC);
             $dataWithJadwal = $row;
             $dataWithJadwal['jadwal'] = $allJadwals;
            echo '<td class="text-nowrap"><button class="btn btn-sm btn-warning me-2" onclick=\'editData(null, ' . $tempat_id . ', ' . json_encode($dataWithJadwal) . ')\'><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger" onclick="deleteData(null, ' . $tempat_id . ', \'' . htmlspecialchars($row['nama_tempat']) . '\')"><i class="fas fa-trash"></i></button></td>';
            break;

        case 'Organisasi':
            // ID Column Dihapus
            echo '<td>' . htmlspecialchars($row['nama_organisasi']) . '</td>';
            echo '<td><button class="btn btn-sm btn-warning me-2" onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama_organisasi']) . '\')"><i class="fas fa-trash"></i></button></td>';
            break;

        case 'kategori_penyakit':
            // ID Column Dihapus
            echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';
            
            // FIX GAMBAR KATEGORI
            echo '<td>';
            if (!empty($row['gambar'])) {
                echo '<img src="' . getCleanedImagePath($row['gambar']) . '" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">';
            } else {
                echo '-';
            }
            echo '</td>';
            
            echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';
            echo '<td><button class="btn btn-sm btn-warning me-2" onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama']) . '\')"><i class="fas fa-trash"></i></button></td>';
            break;

        case 'penyakit':
            echo '<td>' . (!empty($row['kategori_penyakit_nama']) ? '<span class="badge bg-primary">' . htmlspecialchars($row['kategori_penyakit_nama']) . '</span>' : '<span class="badge bg-secondary">Tidak Ada</span>') . '</td>';
            echo '<td>' . htmlspecialchars($row['nama']);
            if (!empty($row['gambar'])) {
                $imageCount = count(array_filter(explode('|', $row['gambar'])));
                if ($imageCount > 0) echo ' <span class="badge bg-info ms-2" title="' . $imageCount . ' gambar"><i class="fas fa-images me-1"></i>' . $imageCount . '</span>';
            }
            echo '</td>';
            echo '<td>' . htmlspecialchars(substr($row['deskripsi_singkat'] ?? '', 0, 50)) . '...</td>';
            
            // FIX GAMBAR PENYAKIT (Looping)
            echo '<td>';
            if (!empty($row['gambar'])) {
                $images = array_filter(explode('|', $row['gambar']));
                if (count($images) > 0) {
                    echo '<div style="display: flex; gap: 5px;">';
                    $maxDisplay = min(count($images), 3);
                    for ($i = 0; $i < $maxDisplay; $i++) {
                        // Pastikan path folder untuk penyakit berbeda (assets/images/penyakit/)
                        $imgFile = basename(trim($images[$i])); 
                        $imgPath = 'assets/images/penyakit/' . $imgFile;
                        echo '<img src="' . htmlspecialchars($imgPath) . '" style="width:40px;height:40px;object-fit:cover;border-radius:4px;cursor:pointer;" onclick="window.open(this.src, \'_blank\')" onerror="this.style.display=\'none\'" title="Klik untuk zoom">';
                    }
                    if (count($images) > 3) echo '<span class="badge bg-secondary align-self-center">+' . (count($images) - 3) . '</span>';
                    echo '</div>';
                } else echo '<span class="text-muted">-</span>';
            } else echo '<span class="text-muted">-</span>';
            echo '</td>';
            
            echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';
            echo '<td><button class="btn btn-sm btn-warning me-2" onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama']) . '\')"><i class="fas fa-trash"></i></button></td>';
            break;
    }
}

// ==========================================
// RENDER FORM (Sama seperti sebelumnya)
// ==========================================
function renderForm($table, $data = null, $categories = [], $tempats = [])
{
    $isEdit = $data !== null;
    switch ($table) {
        case 'jadwal_praktek':
            echo '<div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Nama Tempat</label><input type="text" class="form-control" name="nama_tempat" placeholder="Masukkan nama tempat" required></div><div class="mb-3"><label class="form-label">Alamat</label><input type="text" class="form-control" name="alamat" placeholder="Masukkan alamat" required></div><div class="mb-3"><label class="form-label">No Telp</label><input type="text" class="form-control" name="telp" placeholder="Masukkan nomor telepon" required></div></div><div class="col-md-6"><div id="jadwal-container"><div class="jadwal-item mb-3 border p-2 rounded"><label>Hari</label><input type="text" class="form-control mb-2" name="hari[]" placeholder="Contoh: Senin" required><label>Jam</label><input type="text" class="form-control mb-2" name="waktu[]" placeholder="08:00 - 12:00" required><button type="button" class="btn btn-danger btn-sm remove-jadwal">Hapus</button></div></div><button type="button" id="add-jadwal" class="btn btn-secondary btn-sm mt-2" onclick="tambahJadwalBaru()">+ Tambah Jadwal</button><div class="mb-3 mt-3"><label class="form-label">Link G.maps</label><input type="url" class="form-control" name="gmaps_link" placeholder="Tempel link Google Maps" required></div><div class="mb-3"><label class="form-label">Gambar</label><input type="file" class="form-control" name="gambar" required></div></div></div><script>document.addEventListener("DOMContentLoaded",function(){const e=document.getElementById("addModal");e&&e.addEventListener("click",function(e){"add-jadwal"===e.target.id&&tambahJadwalBaru(),e.target.classList.contains("remove-jadwal")&&e.target.parentElement.remove()})});</script>';
            break;
        case 'Organisasi':
            echo '<div class="mb-3"><label class="form-label">Nama Organisasi</label><input type="text" class="form-control" name="nama_organisasi" value="' . ($isEdit ? htmlspecialchars($data['nama_organisasi']) : '') . '" required></div>';
            break;
        case 'kategori_penyakit':
            $imagePath = $isEdit && !empty($data['gambar']) ? getCleanedImagePath($data['gambar']) : '';
            echo '<div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Nama Kategori Penyakit</label><input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" required></div><div class="mb-3"><label class="form-label">Warna</label><input type="color" class="form-control form-control-color" name="warna" value="' . ($isEdit ? $data['warna'] : '#3b82f6') . '"></div><div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="aktif" ' . ($isEdit && $data['status'] == 'aktif' ? 'selected' : '') . '>Aktif</option><option value="nonaktif" ' . ($isEdit && $data['status'] == 'nonaktif' ? 'selected' : '') . '>Non Aktif</option></select></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="5">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea></div><div class="mb-3"><label class="form-label">Gambar Icon</label>' . ($isEdit && !empty($data['gambar']) ? '<br><img src="' . $imagePath . '" style="max-width:100px;margin-bottom:10px;border-radius:8px;">' : '') . '<input type="file" class="form-control" name="gambar"' . ($isEdit ? '' : ' required') . '><input type="hidden" name="gambar_lama" value="' . ($isEdit ? htmlspecialchars($data['gambar'] ?? '') : '') . '"></div></div></div>';
            break;
        case 'penyakit':
            echo '<div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Kategori Penyakit</label><select class="form-select" name="kategori_id" required><option value="">-- Pilih Kategori Penyakit --</option>';
            if (!empty($categories)) { foreach ($categories as $cat) { $selected = ($isEdit && isset($data['kategori_id']) && $data['kategori_id'] == $cat['id']) ? 'selected' : ''; echo '<option value="' . htmlspecialchars($cat['id']) . '" ' . $selected . '>' . htmlspecialchars($cat['nama']) . '</option>'; } }
            echo '</select></div><div class="mb-3"><label class="form-label">Nama Penyakit</label><input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" required></div><div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="aktif" ' . ($isEdit && $data['status'] == 'aktif' ? 'selected' : '') . '>Aktif</option><option value="nonaktif" ' . ($isEdit && $data['status'] == 'nonaktif' ? 'selected' : '') . '>Non Aktif</option></select></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Deskripsi Singkat</label><textarea class="form-control" name="deskripsi_singkat" rows="3">' . ($isEdit ? htmlspecialchars($data['deskripsi_singkat']) : '') . '</textarea></div></div></div><div class="row"><div class="col-md-6"><div class="mb-3"><label class="form-label">Penyebab Utama</label><textarea class="form-control" name="penyebab_utama" rows="4">' . ($isEdit ? htmlspecialchars($data['penyebab_utama']) : '') . '</textarea></div><div class="mb-3"><label class="form-label">Gejala</label><textarea class="form-control" name="gejala" rows="4">' . ($isEdit ? htmlspecialchars($data['gejala']) : '') . '</textarea></div></div><div class="col-md-6"><div class="mb-3"><label class="form-label">Bahaya</label><textarea class="form-control" name="bahaya" rows="4">' . ($isEdit ? htmlspecialchars($data['bahaya']) : '') . '</textarea></div><div class="mb-3"><label class="form-label">Cara Mencegah</label><textarea class="form-control" name="cara_mencegah" rows="4">' . ($isEdit ? htmlspecialchars($data['cara_mencegah']) : '') . '</textarea></div></div></div><div class="mb-3"><label class="form-label">Cara Mengurangi</label><textarea class="form-control" name="cara_mengurangi" rows="4">' . ($isEdit ? htmlspecialchars($data['cara_mengurangi']) : '') . '</textarea></div><div class="mb-3"><label class="form-label">Gambar (Opsional)</label><input type="file" class="form-control" name="gambar_penyakit[]" multiple accept="image/*" id="gambar_penyakit_input" onchange="previewMultipleImages(this)"></div>';
            break;
    }
}
?>
</body>
</html>