<?php
require_once '../config/Koneksi.php';

$database = new Database();
$db = $database->getConnection();

// Ambil semua kategori penyakit aktif
$query = "SELECT * FROM kategori_organ WHERE status = 'aktif' ORDER BY urutan ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$kategori_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dokter untuk header
$query_dokter = "SELECT * FROM dokter WHERE id = 1";
$stmt_dokter = $db->prepare($query_dokter);
$stmt_dokter->execute();
$dokter = $stmt_dokter->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyakit - <?php echo $dokter['nama']; ?></title>
    <link rel="stylesheet" href="../assets/css/style_fixed.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background-color: #1E3A8A;
            color: white;
            text-align: center;
            padding: 4rem 2rem;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .search-bar {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: none;
            outline: none;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
            padding: 3rem 5%;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .card img {
            width: 70px;
            height: 70px;
            margin-bottom: 1rem;
        }

        .card h3 {
            color: #1E3A8A;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .card p {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 1.2rem;
        }

        .card button {
            background-color: #1E3A8A;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.6rem 1.5rem;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background 0.3s;
        }

        .card button:hover {
            background-color: #FBBF24;
            color: #1E3A8A;
        }

        .lihat-selengkapnya {
            text-align: center;
            margin: 2rem 0 4rem 0;
        }

        .lihat-selengkapnya button {
            background-color: #1E3A8A;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.8rem 2rem;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .lihat-selengkapnya button:hover {
            background-color: #FBBF24;
            color: #1E3A8A;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <section class="hero-section">
        <h1>PENYAKIT</h1>
        <p>Eksplorasi berbagai kategori penyakit dalam berdasarkan sistem organ dan juga karakteristiknya</p>

        <div class="search-bar">
            <input type="text" placeholder="Cari kategori atau penyakit dalam...">
        </div>
    </section>

    <section class="card-container">
        <?php foreach ($kategori_list as $kategori): ?>
        <div class="card">
            <img src="../assets/images/<?php echo htmlspecialchars($kategori['ikon']); ?>" alt="<?php echo htmlspecialchars($kategori['nama']); ?>">
            <h3><?php echo htmlspecialchars($kategori['nama']); ?></h3>
            <p><?php echo htmlspecialchars($kategori['deskripsi']); ?></p>
            <button onclick="window.location.href='penyakit_kategori.php?id=<?php echo $kategori['id']; ?>'">
                Lihat Semua Penyakit
            </button>
        </div>
        <?php endforeach; ?>
    </section>

    <div class="lihat-selengkapnya">
        <button onclick="window.location.href='semua_kategori.php'">Lihat Selengkapnya</button>
    </div>

</body>
</html>
