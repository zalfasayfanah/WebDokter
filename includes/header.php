<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Dokter - <?php echo isset($dokter) ? $dokter['nama'] : 'dr. Arif Rahman'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #1a3c92;
            padding: 25px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        
        .navbar .logo {
            font-weight: bold;
            background-color: #f7c948;
            padding: 8px 20px;
            border-radius: 25px;
            color: #1a237e;
        }
        
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        
        .navbar ul li a {
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .navbar ul li a:hover,
        .navbar ul li a:active {
            background-color: #f7c948;
            color: #1a237e;
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            .navbar ul {
                gap: 10px;
            }
            .navbar ul li a {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">dr. Arif Rahman, Sp.PD</div>
        <ul>
            <li><a href="Index.php">Beranda</a></li>
            <li><a href="Index.php#jadwal">Jadwal Praktek</a></li>
            <li><a href="Penyakit/penyakit_home.php">Penyakit Dalam</a></li>
            <li><a href="Pelayanan.php">Pelayanan</a></li>
        </ul>
    </nav>
