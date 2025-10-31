<?php
session_start();

// Database configuration
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
                "mysql:host=" . $this->host . ";port=3307;dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
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

function handleAdd($db, $table, $data)
{
    $gambar = $_POST['gambar_lama'] ?? '';

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

        case 'keahlian_khusus':
            $stmt = $db->prepare("INSERT INTO keahlian_khusus (nama_keahlian, deskripsi, warna) VALUES (?, ?, ?)");
            $stmt->execute([$data['nama_keahlian'], $data['deskripsi'], $data['warna']]);
            break;

        case 'kategori_organ':
            $stmt = $db->prepare("INSERT INTO kategori_organ (nama, deskripsi, warna, urutan, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['nama'], $data['deskripsi'], $data['warna'], $data['urutan'], $data['status']]);
            break;

        case 'penyakit':
            $stmt = $db->prepare("INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['kategori_id'], $data['nama'], $data['deskripsi_singkat'], $data['penyebab_utama'], $data['gejala'], $data['bahaya'], $data['cara_mencegah'], $data['cara_mengurangi'], $data['status']]);
            break;
    }
}

function handleEdit($db, $table, $data)
{
    $gambar = $_POST['gambar_lama'] ?? '';

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
            $stmt = $db->prepare("UPDATE tempat_praktek SET nama_tempat=?, alamat=?, telp=?, gmaps_link=?, gambar=? WHERE id=?");
            $stmt->execute([$data['nama_tempat'], $data['alamat'], $data['telp'], $data['gmaps_link'] ?? null, $gambar, $data['tempat_id']]);

            $stmt = $db->prepare("DELETE FROM waktu_praktek WHERE tempat_id=?");
            $stmt->execute([$data['tempat_id']]);

            if (!empty($data['hari']) && !empty($data['waktu'])) {
                foreach ($data['hari'] as $i => $hari) {
                    $waktu = $data['waktu'][$i] ?? '';
                    if (!empty($hari) && !empty($waktu)) {
                        $stmt = $db->prepare("INSERT INTO waktu_praktek (tempat_id, hari, waktu) VALUES (?, ?, ?)");
                        $stmt->execute([$data['tempat_id'], $hari, $waktu]);
                    }
                }
            }
            break;

        case 'keahlian_khusus':
            $stmt = $db->prepare("UPDATE keahlian_khusus SET nama_keahlian=?, deskripsi=?, warna=? WHERE id=?");
            $stmt->execute([$data['nama_keahlian'], $data['deskripsi'], $data['warna'], $data['id']]);
            break;

        case 'kategori_organ':
            $stmt = $db->prepare("UPDATE kategori_organ SET nama=?, deskripsi=?, warna=?, urutan=?, status=? WHERE id=?");
            $stmt->execute([$data['nama'], $data['deskripsi'], $data['warna'], $data['urutan'], $data['status'], $data['id']]);
            break;

        case 'penyakit':
            $stmt = $db->prepare("UPDATE penyakit SET kategori_id=?, nama=?, deskripsi_singkat=?, penyebab_utama=?, gejala=?, bahaya=?, cara_mencegah=?, cara_mengurangi=?, status=? WHERE id=?");
            $stmt->execute([$data['kategori_id'], $data['nama'], $data['deskripsi_singkat'], $data['penyebab_utama'], $data['gejala'], $data['bahaya'], $data['cara_mencegah'], $data['cara_mengurangi'], $data['status'], $data['id']]);
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
                SELECT tp.id AS tempat_id, tp.nama_tempat, tp.alamat, tp.telp, tp.gmaps_link, tp.gambar,
                       wp.id AS jadwal_id, wp.hari, wp.waktu
                FROM tempat_praktek tp
                LEFT JOIN waktu_praktek wp ON tp.id = wp.tempat_id
                ORDER BY tp.nama_tempat, wp.hari
            ");
            break;

        case 'keahlian_khusus':
            $stmt = $db->prepare("SELECT * FROM keahlian_khusus ORDER BY nama_keahlian");
            break;

        case 'kategori_organ':
            $stmt = $db->prepare("SELECT * FROM kategori_organ ORDER BY urutan, nama");
            break;

        case 'penyakit':
            $stmt = $db->prepare("SELECT p.*, k.nama as kategori_nama FROM penyakit p LEFT JOIN kategori_organ k ON p.kategori_id = k.id ORDER BY k.nama, p.nama");
            break;
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$data = getData($db, $currentTable);

$tempatsStmt = $db->prepare("SELECT id, nama_tempat, alamat FROM tempat_praktek ORDER BY nama_tempat");
$tempatsStmt->execute();
$tempats = $tempatsStmt->fetchAll(PDO::FETCH_ASSOC);

$categoriesStmt = $db->prepare("SELECT id, nama FROM kategori_organ ORDER BY nama");
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
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
        .form-control, .form-select {
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
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
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
                <a href="?table=keahlian_khusus" class="nav-link <?= $currentTable == 'keahlian_khusus' ? 'active' : '' ?>">
                    <i class="fas fa-star me-2"></i> Keahlian Khusus
                </a>
                <a href="?table=kategori_organ" class="nav-link <?= $currentTable == 'kategori_organ' ? 'active' : '' ?>">
                    <i class="fas fa-list me-2"></i> Kategori Organ
                </a>
                <a href="?table=penyakit" class="nav-link <?= $currentTable == 'penyakit' ? 'active' : '' ?>">
                    <i class="fas fa-virus me-2"></i> Penyakit
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="content-header">
                <h2><i class="fas fa-tachometer-alt me-3"></i>Dashboard Admin</h2>
                <p class="mb-0">Kelola data website medis dengan mudah</p>
            </div>

            <!-- Table Management -->
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
                        <table class="table table-striped table-hover">
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

<!-- Add Modal -->
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

<!-- Edit Modal -->
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
        let jadwals = data.jadwal || [{hari: '', waktu: ''}];
        let jadwalFields = '';
        jadwals.forEach((j, i) => {
            jadwalFields += `
                <div class="jadwal-item mb-3 border p-2 rounded">
                    <label>Hari</label>
                    <input type="text" class="form-control mb-2" name="hari[]" value="${j.hari}" required>
                    <label>Jam</label>
                    <input type="text" class="form-control mb-2" name="waktu[]" value="${j.waktu}" required>
                    <button type="button" class="btn btn-danger btn-sm remove-jadwal">Hapus</button>
                </div>
            `;
        });

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
                    <button type="button" id="add-jadwal" class="btn btn-secondary btn-sm mt-2">+ Tambah Jadwal</button>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Link G.maps</label>
                        <input type="text" class="form-control" name="gmaps_link" value="${data.gmaps_link || ''}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label><br>
                        ${data.gambar ? `<img src="assets/images/${data.gambar}" alt="Preview" style="max-width: 120px; display:block; margin-bottom:10px;">` : ''}
                        <input type="file" class="form-control" name="gambar">
                        <input type="hidden" name="gambar_lama" value="${data.gambar || ''}">
                    </div>
                </div>
            </div>
        `;

        setTimeout(() => {
            const addBtn = document.getElementById('add-jadwal');
            if (addBtn) {
                addBtn.addEventListener('click', () => {
                    const container = document.getElementById('jadwal-container');
                    const item = document.createElement('div');
                    item.classList.add('jadwal-item', 'mb-3', 'border', 'p-2', 'rounded');
                    item.innerHTML = `
                        <label>Hari</label>
                        <input type="text" class="form-control mb-2" name="hari[]" required>
                        <label>Jam</label>
                        <input type="text" class="form-control mb-2" name="waktu[]" required>
                        <button type="button" class="btn btn-danger btn-sm remove-jadwal">Hapus</button>
                    `;
                    container.appendChild(item);
                });
            }

            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-jadwal')) {
                    e.target.parentElement.remove();
                }
            });
        }, 100);
    <?php endif; ?>

    <?php if ($currentTable == 'keahlian_khusus'): ?>
        html = `
            <div class="mb-3">
                <label class="form-label">Nama Keahlian</label>
                <input type="text" class="form-control" name="nama_keahlian" value="${data.nama_keahlian}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="3">${data.deskripsi || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Warna</label>
                <input type="color" class="form-control form-control-color" name="warna" value="${data.warna || '#fbbf24'}">
            </div>
        `;
    <?php endif; ?>

    <?php if ($currentTable == 'kategori_organ'): ?>
        html = `
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" name="nama" value="${data.nama}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="3">${data.deskripsi || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Warna</label>
                <input type="color" class="form-control form-control-color" name="warna" value="${data.warna || '#3b82f6'}">
            </div>
            <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control" name="urutan" value="${data.urutan}" min="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="aktif" ${data.status == 'aktif' ? 'selected' : ''}>Aktif</option>
                    <option value="nonaktif" ${data.status == 'nonaktif' ? 'selected' : ''}>Non Aktif</option>
                </select>
            </div>
        `;
    <?php endif; ?>

    <?php if ($currentTable == 'penyakit'): ?>
        html = `
            <div class="mb-3">
                <label class="form-label">Kategori Organ</label>
                <select class="form-select" name="kategori_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" ${data.kategori_id == <?= $category['id'] ?> ? 'selected' : ''}>
                            <?= htmlspecialchars($category['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Penyakit</label>
                <input type="text" class="form-control" name="nama" value="${data.nama}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control" name="deskripsi_singkat" rows="2">${data.deskripsi_singkat || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Penyebab Utama</label>
                <textarea class="form-control" name="penyebab_utama" rows="3">${data.penyebab_utama || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Gejala</label>
                <textarea class="form-control" name="gejala" rows="3">${data.gejala || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Bahaya</label>
                <textarea class="form-control" name="bahaya" rows="3">${data.bahaya || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Cara Mencegah</label>
                <textarea class="form-control" name="cara_mencegah" rows="3">${data.cara_mencegah || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Cara Mengurangi</label>
                <textarea class="form-control" name="cara_mengurangi" rows="3">${data.cara_mengurangi || ''}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="aktif" ${data.status == 'aktif' ? 'selected' : ''}>Aktif</option>
                    <option value="nonaktif" ${data.status == 'nonaktif' ? 'selected' : ''}>Non Aktif</option>
                </select>
            </div>
        `;
    <?php endif; ?>

    return html;
}
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
        case 'keahlian_khusus':
            echo '<tr><th>ID</th><th>Keahlian</th><th>Deskripsi</th><th>Warna</th><th>Aksi</th></tr>';
            break;
        case 'kategori_organ':
            echo '<tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>';
            break;
        case 'penyakit':
            echo '<tr><th>ID</th><th>Kategori</th><th>Nama</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr>';
            break;
    }
}

// Function to render table rows
function renderTableRow($table, $row, $db)
{
    echo '<tr>';

    switch ($table) {
        case 'jadwal_praktek':
            echo '<td>' . htmlspecialchars($row['nama_tempat'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['alamat'] ?? '', 0, 50)) . '...</td>';
            echo '<td>' . htmlspecialchars($row['telp'] ?? '') . '</td>';

            echo '<td>';
            $jadwalQuery = $db->prepare("SELECT hari FROM waktu_praktek WHERE tempat_id=?");
            $jadwalQuery->execute([$row['tempat_id']]);
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
            $jamQuery = $db->prepare("SELECT waktu FROM waktu_praktek WHERE tempat_id=?");
            $jamQuery->execute([$row['tempat_id']]);
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
                echo '<img src="assets/images/' . htmlspecialchars($row['gambar']) . '">';
            } else {
                echo 'Tidak ada gambar';
            }
            echo '</td>';

            $tempatId = $row['tempat_id'] ?? null;
            $jadwalFullQuery = $db->prepare("SELECT hari, waktu FROM waktu_praktek WHERE tempat_id=?");
            $jadwalFullQuery->execute([$tempatId]);
            $allJadwals = $jadwalFullQuery->fetchAll(PDO::FETCH_ASSOC);

            $dataWithJadwal = $row;
            $dataWithJadwal['jadwal'] = $allJadwals;

            echo '<td class="text-nowrap">';
            echo '<button class="btn btn-sm btn-warning me-2" 
                onclick=\'editData(null, ' . $tempatId . ', ' . json_encode($dataWithJadwal) . ')\'>
                <i class="fas fa-edit"></i>
            </button>';
            echo '<button class="btn btn-sm btn-danger" 
                onclick="deleteData(null, ' . $tempatId . ', \'' . htmlspecialchars($row['nama_tempat']) . '\')">
                <i class="fas fa-trash"></i>
            </button>';
            echo '</td>';
            break;

        case 'keahlian_khusus':
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['nama_keahlian']) . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';
            echo '<td><span class="badge" style="background-color: ' . $row['warna'] . '">' . $row['warna'] . '</span></td>';

            echo '<td>';
            echo '<button class="btn btn-sm btn-warning me-2" 
                onclick="editData(' . $row['id'] . ', null, ' . htmlspecialchars(json_encode($row)) . ')">
                <i class="fas fa-edit"></i>
            </button>';
            echo '<button class="btn btn-sm btn-danger" 
                onclick="deleteData(' . $row['id'] . ', null, \'' . htmlspecialchars($row['nama_keahlian']) . '\')">
                <i class="fas fa-trash"></i>
            </button>';
            echo '</td>';
            break;

        case 'kategori_organ':
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';
            echo '<td>' . $row['urutan'] . '</td>';
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
            echo '<td>' . htmlspecialchars($row['kategori_nama'] ?? '') . '</td>';
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
                    <button type="button" id="add-jadwal" class="btn btn-secondary btn-sm mt-2">+ Tambah Jadwal</button>

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
                document.getElementById("add-jadwal").addEventListener("click", function() {
                    const container = document.getElementById("jadwal-container");
                    const item = document.createElement("div");
                    item.classList.add("jadwal-item", "mb-3", "border", "p-2", "rounded");
                    item.innerHTML = `
                        <label>Hari</label>
                        <input type="text" class="form-control mb-2" name="hari[]" required>
                        <label>Jam</label>
                        <input type="text" class="form-control mb-2" name="waktu[]" required>
                        <button type="button" class="btn btn-danger btn-sm remove-jadwal">Hapus</button>
                    `;
                    container.appendChild(item);
                });

                document.addEventListener("click", function(e) {
                    if (e.target.classList.contains("remove-jadwal")) {
                        e.target.parentElement.remove();
                    }
                });
            </script>
            ';
            break;

        case 'keahlian_khusus':
            echo '<div class="mb-3">
                <label class="form-label">Nama Keahlian</label>
                <input type="text" class="form-control" name="nama_keahlian" value="' . ($isEdit ? htmlspecialchars($data['nama_keahlian']) : '') . '" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="3">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Warna</label>
                <input type="color" class="form-control form-control-color" name="warna" value="' . ($isEdit ? $data['warna'] : '#fbbf24') . '">
            </div>';
            break;

        case 'kategori_organ':
            echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="' . ($isEdit ? $data['urutan'] : '0') . '" min="0">
                    </div>
                </div>
                <div class="col-md-6">
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
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="3">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
            </div>';
            break;

        case 'penyakit':
            echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kategori Organ</label>
                        <select class="form-select" name="kategori_id" required>';
            echo '<option value="">Pilih Kategori</option>';
            foreach ($categories as $category) {
                $selected = ($isEdit && $data['kategori_id'] == $category['id']) ? 'selected' : '';
                echo '<option value="' . $category['id'] . '" ' . $selected . '>' . htmlspecialchars($category['nama']) . '</option>';
            }
            echo '</select>
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

</body>
</html>