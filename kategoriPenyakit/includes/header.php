<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Dokter - <?php echo isset($dokter) ? $dokter['nama'] : 'dr. Arif Rahman'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .navbar {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 0.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }
        
        .doctor-badge {
            background: #fbbf24;
            color: #1e3a8a;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-menu a:hover {
            color: #fbbf24;
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
        }
        body {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="doctor-badge">
                <?php echo isset($dokter) ? $dokter['nama'] . ', ' . $dokter['gelar'] : 'dr. Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua'; ?>
            </div>
            <ul class="nav-menu">
                <li><a href="../Index.php">Beranda</a></li>
                <li><a href="../Index.php#jadwal">Jadwal Praktek</a></li>
                <li><a href="penyakit.php">Penyakit</a></li>
                <li><a href="../Pelayanan.php">Pelayanan</a></li>
            </ul>
        </div>
    </nav>