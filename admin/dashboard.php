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
            handleEdit($db, $table, $_POST);
            break;
        case 'delete':
            handleDelete($db, $table, $_POST['id']);
            break;
    }

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?table=" . $table);
    exit();
}

function handleAdd($db, $table, $data)
{
    // --- Handle Upload Gambar ---
$gambar = $_POST['gambar_lama'] ?? ''; // default gambar lama kalau tidak diubah

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $targetDir = __DIR__ . "/assets/images/"; // path folder simpan
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['gambar']['name']); // biar unik
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
        $gambar = $fileName; // update nama file baru
    }
}

// sekarang $gambar siap dipakai di query INSERT / UPDATE

    switch ($table) {
        case 'dokter':
            $stmt = $db->prepare("INSERT INTO dokter (nama, spesialisasi, gelar, deskripsi, telepon, email) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['nama'], $data['spesialisasi'], $data['gelar'], $data['deskripsi'], $data['telepon'], $data['email']]);
            break;

        // case 'jadwal_praktek':
        //     $stmt = $db->prepare("INSERT INTO jadwal_praktek (dokter_id, nama_tempat, alamat, hari, jam_mulai, jam_selesai, telepon, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        //     $stmt->execute([$data['dokter_id'], $data['nama_tempat'], $data['alamat'], $data['hari'], $data['jam_mulai'], $data['jam_selesai'], $data['telepon'], $data['status']]);
        //     break;

        case 'jadwal_praktek':
            // Validasi input tempat praktek
            if (
                empty($data['nama_tempat']) || empty($data['alamat']) || empty($data['telp'])
                || empty($data['gmaps_link']) || empty($data['gambar'])
            ) {
                die("Harap lengkapi semua data tempat praktek sebelum menyimpan.");
            }

            // Insert data tempat praktek
            $stmt = $db->prepare("
        INSERT INTO tempat_praktek (nama_tempat, alamat, telp, gmaps_link, gambar) 
        VALUES (?, ?, ?, ?, ?)
    ");
            $stmt->execute([
                $data['nama_tempat'],
                $data['alamat'],
                $data['telp'],
                $data['gmaps_link'],
                $data['gambar']
            ]);

            // Ambil ID tempat praktek yg baru ditambahkan
            $id_tempat = $db->lastInsertId();

            // Validasi input waktu praktek
            if (empty($data['hari']) || empty($data['waktu'])) {
                die("Harap lengkapi semua data waktu praktek sebelum menyimpan.");
            }

            // Insert data waktu praktek
            $stmt = $db->prepare("
        INSERT INTO waktu_praktek (tempat_id, hari, waktu) 
        VALUES (?, ?, ?)
    ");
            $stmt->execute([
                $id_tempat,
                $data['hari'],
                $data['waktu']
            ]);
            break;


        case 'sertifikat':
            $stmt = $db->prepare("INSERT INTO sertifikat (dokter_id, nama_sertifikat, institusi, tahun, deskripsi) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['dokter_id'], $data['nama_sertifikat'], $data['institusi'], $data['tahun'], $data['deskripsi']]);
            break;

        case 'keahlian_khusus':
            $stmt = $db->prepare("INSERT INTO keahlian_khusus (dokter_id, nama_keahlian, deskripsi, warna) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['dokter_id'], $data['nama_keahlian'], $data['deskripsi'], $data['warna']]);
            break;

        case 'kategori_organ':
            $stmt = $db->prepare("INSERT INTO kategori_organ (nama, deskripsi, warna, urutan, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['nama'], $data['deskripsi'], $data['warna'], $data['urutan'], $data['status']]);
            break;

        case 'penyakit':
            $stmt = $db->prepare("INSERT INTO penyakit (kategori_id, nama, deskripsi_singkat, penyebab_utama, gejala, bahaya, cara_mencegah, cara_mengurangi, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['kategori_id'], $data['nama'], $data['deskripsi_singkat'], $data['penyebab_utama'], $data['gejala'], $data['bahaya'], $data['cara_mencegah'], $data['cara_mengurangi'], $data['status']]);
            break;

        case 'layanan_medis':
            $stmt = $db->prepare("INSERT INTO layanan_medis (nama, deskripsi, link_eksternal, urutan, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['nama'], $data['deskripsi'], $data['link_eksternal'], $data['urutan'], $data['status']]);
            break;
    }
}

function handleEdit($db, $table, $data)
{
    $id = $data['id'];
    // --- Handle Upload Gambar ---
$gambar = $_POST['gambar_lama'] ?? ''; // default gambar lama kalau tidak diubah

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $targetDir = __DIR__ . "/assets/images/"; // path folder simpan
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['gambar']['name']); // biar unik
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFilePath)) {
        $gambar = $fileName; // update nama file baru
    }
}

// sekarang $gambar siap dipakai di query INSERT / UPDATE

    switch ($table) {
        case 'dokter':
            $stmt = $db->prepare("UPDATE dokter SET nama=?, spesialisasi=?, gelar=?, deskripsi=?, telepon=?, email=? WHERE id=?");
            $stmt->execute([$data['nama'], $data['spesialisasi'], $data['gelar'], $data['deskripsi'], $data['telepon'], $data['email'], $id]);
            break;

        // case 'jadwal_praktek':
        //     $stmt = $db->prepare("UPDATE jadwal_praktek SET dokter_id=?, nama_tempat=?, alamat=?, hari=?, jam_mulai=?, jam_selesai=?, telepon=?, status=? WHERE id=?");
        //     $stmt->execute([$data['dokter_id'], $data['nama_tempat'], $data['alamat'], $data['hari'], $data['jam_mulai'], $data['jam_selesai'], $data['telepon'], $data['status'], $id]);
        //     break;

        case 'jadwal_praktek':
            // Update data tempat praktek
            $stmt = $db->prepare("UPDATE tempat_praktek 
                  SET nama_tempat=?, alamat=?, telp=?, gmaps_link=?, gambar=? 
                  WHERE id=?");
            $stmt->execute([
                $data['nama_tempat'],
                $data['alamat'],
                $data['telp'],
                $data['gmaps_link'] ?? null,
                $data['gambar'] ?? null,
                $data['tempat_id']   // ini harus id dari tempat_praktek
            ]);

            // Update data waktu praktek
            $stmt = $db->prepare("UPDATE waktu_praktek 
                  SET hari=?, waktu=? 
                  WHERE id=?");
            $stmt->execute([
                $data['hari'],
                $data['waktu'],
                $id   // ini adalah id dari waktu_praktek
            ]);
            break;


        case 'sertifikat':
            $stmt = $db->prepare("UPDATE sertifikat SET dokter_id=?, nama_sertifikat=?, institusi=?, tahun=?, deskripsi=? WHERE id=?");
            $stmt->execute([$data['dokter_id'], $data['nama_sertifikat'], $data['institusi'], $data['tahun'], $data['deskripsi'], $id]);
            break;

        case 'keahlian_khusus':
            $stmt = $db->prepare("UPDATE keahlian_khusus SET dokter_id=?, nama_keahlian=?, deskripsi=?, warna=? WHERE id=?");
            $stmt->execute([$data['dokter_id'], $data['nama_keahlian'], $data['deskripsi'], $data['warna'], $id]);
            break;

        case 'kategori_organ':
            $stmt = $db->prepare("UPDATE kategori_organ SET nama=?, deskripsi=?, warna=?, urutan=?, status=? WHERE id=?");
            $stmt->execute([$data['nama'], $data['deskripsi'], $data['warna'], $data['urutan'], $data['status'], $id]);
            break;

        case 'penyakit':
            $stmt = $db->prepare("UPDATE penyakit SET kategori_id=?, nama=?, deskripsi_singkat=?, penyebab_utama=?, gejala=?, bahaya=?, cara_mencegah=?, cara_mengurangi=?, status=? WHERE id=?");
            $stmt->execute([$data['kategori_id'], $data['nama'], $data['deskripsi_singkat'], $data['penyebab_utama'], $data['gejala'], $data['bahaya'], $data['cara_mencegah'], $data['cara_mengurangi'], $data['status'], $id]);
            break;

        case 'layanan_medis':
            $stmt = $db->prepare("UPDATE layanan_medis SET nama=?, deskripsi=?, link_eksternal=?, urutan=?, status=? WHERE id=?");
            $stmt->execute([$data['nama'], $data['deskripsi'], $data['link_eksternal'], $data['urutan'], $data['status'], $id]);
            break;
    }
}

function handleDelete($db, $table, $id)
{
    $stmt = $db->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$id]);
}

// Get current table
$currentTable = $_GET['table'] ?? 'dokter';

// Get data for current table
function getData($db, $table)
{
    switch ($table) {
        case 'dokter':
            $stmt = $db->prepare("SELECT * FROM dokter ORDER BY nama");
            break;
        // case 'jadwal_praktek':
        //     $stmt = $db->prepare("SELECT jp.*, d.nama as dokter_nama FROM jadwal_praktek jp LEFT JOIN dokter d ON jp.dokter_id = d.id ORDER BY d.nama, jp.hari");
        //     break;
        case 'jadwal_praktek':
            $stmt = $db->prepare("
        SELECT 
            wp.id AS id,
            wp.hari,
            wp.waktu,
            tp.id AS tempat_id,
            tp.nama_tempat,
            tp.alamat,
            tp.telp,
            tp.gmaps_link,
            tp.gambar
        FROM waktu_praktek wp
        LEFT JOIN tempat_praktek tp ON wp.tempat_id = tp.id
        ORDER BY tp.nama_tempat, wp.hari
    ");
            break;


        case 'sertifikat':
            $stmt = $db->prepare("SELECT s.*, d.nama as dokter_nama FROM sertifikat s LEFT JOIN dokter d ON s.dokter_id = d.id ORDER BY d.nama, s.tahun DESC");
            break;
        case 'keahlian_khusus':
            $stmt = $db->prepare("SELECT k.*, d.nama as dokter_nama FROM keahlian_khusus k LEFT JOIN dokter d ON k.dokter_id = d.id ORDER BY d.nama, k.nama_keahlian");
            break;
        case 'kategori_organ':
            $stmt = $db->prepare("SELECT * FROM kategori_organ ORDER BY urutan, nama");
            break;
        case 'penyakit':
            $stmt = $db->prepare("SELECT p.*, k.nama as kategori_nama FROM penyakit p LEFT JOIN kategori_organ k ON p.kategori_id = k.id ORDER BY k.nama, p.nama");
            break;
        case 'layanan_medis':
            $stmt = $db->prepare("SELECT * FROM layanan_medis ORDER BY urutan, nama");
            break;
        default:
            $stmt = $db->prepare("SELECT * FROM dokter ORDER BY nama");
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$data = getData($db, $currentTable);

// Ambil data tempat praktek
$tempatsStmt = $db->prepare("SELECT id, nama_tempat, alamat FROM tempat_praktek ORDER BY nama_tempat");
$tempatsStmt->execute();
$tempats = $tempatsStmt->fetchAll(PDO::FETCH_ASSOC);

// Get doctors for dropdown
$doctorsStmt = $db->prepare("SELECT id, nama FROM dokter ORDER BY nama");
$doctorsStmt->execute();
$doctors = $doctorsStmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories for dropdown
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
            border-radius: 15px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
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

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding-left: 40px;
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #667eea;
            font-size: 18px;
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            transition: transform 0.2s;
        }

        .btn-primary:hover {
            transform: scale(1.05);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border: none;
            border-radius: 25px;
        }

        .btn-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            border-radius: 25px;
        }

        .mb-3.position-relative {
            position: relative;
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
                    <a href="?table=dokter" class="nav-link <?= $currentTable == 'dokter' ? 'active' : '' ?>">
                        <i class="fas fa-user-md me-2"></i> Dokter
                    </a>
                    <a href="?table=jadwal_praktek"
                        class="nav-link <?= $currentTable == 'jadwal_praktek' ? 'active' : '' ?>">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Praktek
                    </a>
                    <a href="?table=sertifikat" class="nav-link <?= $currentTable == 'sertifikat' ? 'active' : '' ?>">
                        <i class="fas fa-certificate me-2"></i> Sertifikat
                    </a>
                    <a href="?table=keahlian_khusus"
                        class="nav-link <?= $currentTable == 'keahlian_khusus' ? 'active' : '' ?>">
                        <i class="fas fa-star me-2"></i> Keahlian Khusus
                    </a>
                    <a href="?table=kategori_organ"
                        class="nav-link <?= $currentTable == 'kategori_organ' ? 'active' : '' ?>">
                        <i class="fas fa-list me-2"></i> Kategori Organ
                    </a>
                    <a href="?table=penyakit" class="nav-link <?= $currentTable == 'penyakit' ? 'active' : '' ?>">
                        <i class="fas fa-virus me-2"></i> Penyakit
                    </a>
                    <a href="?table=layanan_medis"
                        class="nav-link <?= $currentTable == 'layanan_medis' ? 'active' : '' ?>">
                        <i class="fas fa-hospital me-2"></i> Layanan Medis
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
                                        <?php renderTableRow($currentTable, $row); ?>
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
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="table" value="<?= $currentTable ?>">
                        <?php renderForm($currentTable, null, $doctors, $categories, $tempats); ?>
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
                <form method="POST">
                    <div class="modal-body">
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
        function editData(id, data) {
            document.getElementById('edit_id').value = id;
            const formContent = document.getElementById('editFormContent');
            formContent.innerHTML = generateEditForm(data);
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function deleteData(id, name) {
            if (confirm('Apakah Anda yakin ingin menghapus "' + name + '"?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="table" value="<?= $currentTable ?>">
            <input type="hidden" name="id" value="${id}">
        `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function generateEditForm(data) {
            // This would generate the edit form based on current table and data
            // For brevity, I'll implement a simplified version
            let html = '';

            <?php if ($currentTable == 'dokter'): ?>
                html = `
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Dokter</label>
                    <input type="text" class="form-control" name="nama" value="${data.nama}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" class="form-control" name="spesialisasi" value="${data.spesialisasi}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gelar</label>
                    <input type="text" class="form-control" name="gelar" value="${data.gelar}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" class="form-control" name="telepon" value="${data.telepon}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="${data.email}">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" rows="4">${data.deskripsi || ''}</textarea>
        </div>
    `;
            <?php elseif ($currentTable == 'jadwal_praktek'): ?>
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
                <div class="mb-3">
                    <label class="form-label">Hari</label>
                    <input type="text" class="form-control" name="hari" value="${data.hari}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jam</label>
                    <input type="text" class="form-control" name="waktu" value="${data.waktu}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link Gmaps</label>
                    <input type="text" class="form-control" name="gmaps_link" value="${data.gmaps_link || ''}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar</label><br>
                    ${data.gambar 
                        ? `<img src="assets/images/${data.gambar}" alt="Preview" style="max-width: 120px; display:block; margin-bottom:10px;">` 
                        : ''
                    }
                    <input type="file" class="form-control" name="gambar">
                    <input type="hidden" name="gambar_lama" value="${data.gambar || ''}">
                </div>
            </div>
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
            case 'dokter':
                echo '<tr><th>ID</th><th>Nama</th><th>Spesialisasi</th><th>Telepon</th><th>Email</th><th>Aksi</th></tr>';
                break;
            // case 'jadwal_praktek':
            //     echo '<tr><th>ID</th><th>Dokter</th><th>Tempat</th><th>Alamat</th><th>Hari</th><th>Jam</th><th>Status</th><th>Aksi</th></tr>';
            //     break;

            case 'jadwal_praktek':
                echo '
        <tr>
            
            <th>Nama Tempat</th>
            <th>Alamat</th>
            <th>No Telp</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Link G.maps</th>
            <th>Gambar</th>
        </tr>
    ';
                break;


            case 'sertifikat':
                echo '<tr><th>ID</th><th>Dokter</th><th>Sertifikat</th><th>Institusi</th><th>Tahun</th><th>Aksi</th></tr>';
                break;
            case 'keahlian_khusus':
                echo '<tr><th>ID</th><th>Dokter</th><th>Keahlian</th><th>Deskripsi</th><th>Aksi</th></tr>';
                break;
            case 'kategori_organ':
                echo '<tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>';
                break;
            case 'penyakit':
                echo '<tr><th>ID</th><th>Kategori</th><th>Nama</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr>';
                break;
            case 'layanan_medis':
                echo '<tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>';
                break;
        }
    }

    // Function to render table rows
    function renderTableRow($table, $row)
    {
        echo '<tr>';

        switch ($table) {
            case 'dokter':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                echo '<td>' . htmlspecialchars($row['spesialisasi']) . '</td>';
                echo '<td>' . htmlspecialchars($row['telepon'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['email'] ?? '') . '</td>';
                break;
            // case 'jadwal_praktek':
            //     echo '<td>' . $row['id'] . '</td>';
            //     echo '<td>' . htmlspecialchars($row['dokter_nama'] ?? '') . '</td>';
            //     echo '<td>' . htmlspecialchars($row['nama_tempat']) . '</td>';
            //     echo '<td>' . htmlspecialchars(substr($row['alamat'] ?? '', 0, 50)) . '...</td>';
            //     echo '<td>' . htmlspecialchars($row['hari']) . '</td>';
            //     echo '<td>' . $row['jam_mulai'] . ' - ' . $row['jam_selesai'] . '</td>';
            //     echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';
            //     break;

            case 'jadwal_praktek':
                echo '<td>' . htmlspecialchars($row['nama_tempat'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['alamat'] ?? '', 0, 50)) . '...</td>';
                echo '<td>' . htmlspecialchars($row['telp'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['hari'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['waktu'] ?? '') . '</td>';
                echo '<td><a href="' . htmlspecialchars($row['gmaps_link'] ?? '#') . '" target="_blank">Lihat Peta</a></td>';
                echo '<td>';
                if (!empty($row['gambar'])) {
                    echo '<img src="assets/images/' . htmlspecialchars($row['gambar']) . '" alt="Gambar" width="80">';
                } else {
                    echo 'Tidak ada gambar';
                }
                echo '</td>';
                break;


            case 'sertifikat':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['dokter_nama'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['nama_sertifikat']) . '</td>';
                echo '<td>' . htmlspecialchars($row['institusi'] ?? '') . '</td>';
                echo '<td>' . $row['tahun'] . '</td>';
                break;
            case 'keahlian_khusus':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['dokter_nama'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['nama_keahlian']) . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';
                break;
            case 'kategori_organ':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';
                echo '<td>' . $row['urutan'] . '</td>';
                echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';
                break;
            case 'penyakit':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['kategori_nama'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['deskripsi_singkat'] ?? '', 0, 50)) . '...</td>';
                echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';
                break;
            case 'layanan_medis':
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                echo '<td>' . htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 50)) . '...</td>';
                echo '<td>' . $row['urutan'] . '</td>';
                echo '<td><span class="badge bg-' . ($row['status'] == 'aktif' ? 'success' : 'secondary') . '">' . $row['status'] . '</span></td>';
                break;
        }

        echo '<td>';
        echo '<button class="btn btn-sm btn-warning me-2" onclick="editData(' . $row['id'] . ', ' . htmlspecialchars(json_encode($row)) . ')"><i class="fas fa-edit"></i></button>';
        echo '<button class="btn btn-sm btn-danger" onclick="deleteData(' . $row['id'] . ', \'' . htmlspecialchars($row['nama'] ?? $row['nama_tempat'] ?? $row['nama_sertifikat'] ?? $row['nama_keahlian']) . '\')"><i class="fas fa-trash"></i></button>';
        echo '</td>';

        echo '</tr>';
    }

    // Function to render forms
    function renderForm($table, $data = null, $doctors = [], $categories = [], $tempats = [])
    {
        $isEdit = $data !== null;

        switch ($table) {
            case 'dokter':
                echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Dokter</label>
                        <input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spesialisasi</label>
                        <input type="text" class="form-control" name="spesialisasi" value="' . ($isEdit ? htmlspecialchars($data['spesialisasi']) : '') . '" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gelar</label>
                        <input type="text" class="form-control" name="gelar" value="' . ($isEdit ? htmlspecialchars($data['gelar']) : '') . '">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" class="form-control" name="telepon" value="' . ($isEdit ? htmlspecialchars($data['telepon']) : '') . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="' . ($isEdit ? htmlspecialchars($data['email']) : '') . '">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="4">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
            </div>';
                break;

            // case 'jadwal_praktek':
            //     echo '<div class="row">
            //     <div class="col-md-6">
            //         <div class="mb-3">
            //             <label class="form-label">Dokter</label>
            //             <select class="form-select" name="dokter_id" required>';
            //     echo '<option value="">Pilih Dokter</option>';
            //     foreach ($doctors as $doctor) {
            //         $selected = ($isEdit && $data['dokter_id'] == $doctor['id']) ? 'selected' : '';
            //         echo '<option value="' . $doctor['id'] . '" ' . $selected . '>' . htmlspecialchars($doctor['nama']) . '</option>';
            //     }
            //     echo '</select>
            //         </div>
            //         <div class="mb-3">
            //             <label class="form-label">Nama Tempat</label>
            //             <input type="text" class="form-control" name="nama_tempat" value="' . ($isEdit ? htmlspecialchars($data['nama_tempat']) : '') . '" required>
            //         </div>
            //         <div class="mb-3">
            //             <label class="form-label">Hari</label>
            //             <input type="text" class="form-control" name="hari" value="' . ($isEdit ? htmlspecialchars($data['hari']) : '') . '" placeholder="Contoh: Senin, Rabu, Jumat">
            //         </div>
            //         <div class="mb-3">
            //             <label class="form-label">Status</label>
            //             <select class="form-select" name="status">
            //                 <option value="aktif" ' . ($isEdit && $data['status'] == 'aktif' ? 'selected' : '') . '>Aktif</option>
            //                 <option value="nonaktif" ' . ($isEdit && $data['status'] == 'nonaktif' ? 'selected' : '') . '>Non Aktif</option>
            //             </select>
            //         </div>
            //     </div>
            //     <div class="col-md-6">
            //         <div class="mb-3">
            //             <label class="form-label">Jam Mulai</label>
            //             <input type="time" class="form-control" name="jam_mulai" value="' . ($isEdit ? $data['jam_mulai'] : '') . '">
            //         </div>
            //         <div class="mb-3">
            //             <label class="form-label">Jam Selesai</label>
            //             <input type="time" class="form-control" name="jam_selesai" value="' . ($isEdit ? $data['jam_selesai'] : '') . '">
            //         </div>
            //         <div class="mb-3">
            //             <label class="form-label">Telepon</label>
            //             <input type="text" class="form-control" name="telepon" value="' . ($isEdit ? htmlspecialchars($data['telepon']) : '') . '">
            //         </div>
            //     </div>
            // </div>
            // <div class="mb-3">
            //     <label class="form-label">Alamat</label>
            //     <textarea class="form-control" name="alamat" rows="3">' . ($isEdit ? htmlspecialchars($data['alamat']) : '') . '</textarea>
            // </div>';
            //     break;

            case 'jadwal_praktek':
                echo '<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Nama Tempat</label>
            <input type="text" class="form-control" name="nama_tempat" 
                value="' . ($isEdit ? htmlspecialchars($data['nama_tempat']) : '') . '" 
                placeholder="Masukkan nama tempat" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" class="form-control" name="alamat" 
                value="' . ($isEdit ? htmlspecialchars($data['alamat']) : '') . '" 
                placeholder="Masukkan alamat" required>
        </div>
        <div class="mb-3">
            <label class="form-label">No Telp</label>
            <input type="text" class="form-control" name="telp" 
                value="' . ($isEdit ? htmlspecialchars($data['telp']) : '') . '" 
                placeholder="Masukkan nomor telepon" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Hari</label>
            <input type="text" class="form-control" name="hari" 
                value="' . ($isEdit ? htmlspecialchars($data['hari']) : '') . '" 
                placeholder="Contoh: Senin, Rabu, Jumat" required>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Jam</label>
            <input type="text" class="form-control" name="waktu" 
                value="' . ($isEdit ? htmlspecialchars($data['waktu']) : '') . '" 
                placeholder="Contoh: 08:00 - 12:00" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Link G.maps</label>
            <input type="url" class="form-control" name="gmaps_link" 
                value="' . ($isEdit ? htmlspecialchars($data['gmaps_link']) : '') . '" 
                placeholder="Tempel link Google Maps" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" class="form-control" name="gambar" ' . ($isEdit ? '' : 'required') . '>
        </div>
    </div>
</div>';
                break;



            case 'sertifikat':
                echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Dokter</label>
                        <select class="form-select" name="dokter_id" required>';
                echo '<option value="">Pilih Dokter</option>';
                foreach ($doctors as $doctor) {
                    $selected = ($isEdit && $data['dokter_id'] == $doctor['id']) ? 'selected' : '';
                    echo '<option value="' . $doctor['id'] . '" ' . $selected . '>' . htmlspecialchars($doctor['nama']) . '</option>';
                }
                echo '</select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Sertifikat</label>
                        <input type="text" class="form-control" name="nama_sertifikat" value="' . ($isEdit ? htmlspecialchars($data['nama_sertifikat']) : '') . '" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Institusi</label>
                        <input type="text" class="form-control" name="institusi" value="' . ($isEdit ? htmlspecialchars($data['institusi']) : '') . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" class="form-control" name="tahun" value="' . ($isEdit ? $data['tahun'] : '') . '" min="1900" max="2030">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="3">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
            </div>';
                break;

            case 'keahlian_khusus':
                echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Dokter</label>
                        <select class="form-select" name="dokter_id" required>';
                echo '<option value="">Pilih Dokter</option>';
                foreach ($doctors as $doctor) {
                    $selected = ($isEdit && $data['dokter_id'] == $doctor['id']) ? 'selected' : '';
                    echo '<option value="' . $doctor['id'] . '" ' . $selected . '>' . htmlspecialchars($doctor['nama']) . '</option>';
                }
                echo '</select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Keahlian</label>
                        <input type="text" class="form-control" name="nama_keahlian" value="' . ($isEdit ? htmlspecialchars($data['nama_keahlian']) : '') . '" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <input type="color" class="form-control form-control-color" name="warna" value="' . ($isEdit ? $data['warna'] : '#fbbf24') . '">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="3">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
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

            case 'layanan_medis':
                echo '<div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" class="form-control" name="nama" value="' . ($isEdit ? htmlspecialchars($data['nama']) : '') . '" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Eksternal</label>
                        <input type="url" class="form-control" name="link_eksternal" value="' . ($isEdit ? htmlspecialchars($data['link_eksternal']) : '') . '" placeholder="https://example.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="' . ($isEdit ? $data['urutan'] : '0') . '" min="0">
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
                <textarea class="form-control" name="deskripsi" rows="4">' . ($isEdit ? htmlspecialchars($data['deskripsi']) : '') . '</textarea>
            </div>';
                break;
        }
    }
    ?>

</body>

</html>