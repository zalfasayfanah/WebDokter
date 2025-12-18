<?php 
  include 'config/Koneksi.php';
  include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelayanan - dr. Arif Rahman, Sp.PD</title>
    <style>
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

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: #ffffff;
    color: #1e293b;
    line-height: 1.6;
}

.hero {
    background: linear-gradient(to bottom, #1e40af 0%, #1e3a8a 100%);
    padding: 60px 24px 50px;
    text-align: center;
}

.hero h1 {
    margin: 0 0 16px 0;
    font-size: 30px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.3;
}

.hero p {
    max-width: 720px;
    margin: 0 auto;
    font-size: 15px;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.7;
    font-weight: 400;
}

.hero p strong {
    color: #fbbf24;
    font-weight: 600;
}

.section-title {
    text-align: center;
    margin: 56px 0 40px;
    padding: 0 24px;
}

.section-title h2 {
    font-size: 24px;
    font-weight: 700;
    color: #173f9cff;
    margin: 0 0 8px 0;
}

.section-subtitle {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

/* Cards Grid */
.cards {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 24px 100px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 28px;
}

.card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    position: relative;
    cursor: default;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    transition: none;
}

.card:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.card:active {
    transform: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

/* Medical accent color on top */
.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0c4a6e 0%, #0284c7 100%);
}

/* Icon Container */
.icon-container {
    width: 100%;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    padding: 0;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Card Content */
.card-content {
    padding: 28px 24px;
    background: #ffffff;
    text-align: center;
}

.card-content p {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
    color: #062b83ff;
    line-height: 1.5;
}

.card-content p br {
    display: block;
    content: "";
    margin-top: 4px;
}

.card-subtitle {
    display: block;
    font-size: 13px;
    color: #64748b;
    font-weight: 400;
    margin-top: 8px;
}

/* Trust Badge */
.trust-section {
    max-width: 1400px;
    margin: 0 auto 80px;
    padding: 0 24px;
    text-align: center;
}

.trust-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: #f0f9ff;
    padding: 16px 32px;
    border-radius: 50px;
    border: 2px solid #bae6fd;
}

.trust-badge-icon {
    width: 24px;
    height: 24px;
    background: #0284c7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.trust-badge-text {
    font-size: 15px;
    color: #2780b4ff;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .cards {
        max-width: 1200px;
        gap: 24px;
    }
}

@media (max-width: 1200px) {
    .cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    .section-title h2 {
        font-size: 22px;
    }
}

@media (max-width: 1024px) {
    .hero h1 {
        font-size: 28px;
    }
    
    .hero p {
        font-size: 15px;
    }
    
    .section-title h2 {
        font-size: 21px;
    }
    
    .icon-container {
        height: 200px;
    }
}

@media (max-width: 768px) {
    .hero {
        padding: 56px 20px 48px;
    }
    
    .hero h1 {
        font-size: 24px;
    }
    
    .hero-subtitle {
        font-size: 13px;
        padding: 6px 16px;
    }
    
    .hero p {
        font-size: 14px;
    }
    
    .section-title {
        margin: 52px 0 36px;
    }
    
    .section-title h2 {
        font-size: 20px;
    }
    
    .section-subtitle {
        font-size: 13px;
    }
    
    .cards {
        padding: 0 20px 70px;
        gap: 20px;
        grid-template-columns: 1fr;
    }
    
    .icon-container {
        height: 180px;
        padding: 0 20px;
    }
    
    .card-content {
        padding: 24px 20px;
    }
    
    .card-content p {
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .hero {
        padding: 48px 16px 40px;
    }
    
    .hero h1 {
        font-size: 21px;
    }
    
    .hero-subtitle {
        font-size: 12px;
        padding: 5px 14px;
    }
    
    .hero p {
        font-size: 13px;
        line-height: 1.7;
    }
    
    .section-title {
        margin: 44px 0 32px;
    }
    
    .section-title h2 {
        font-size: 18px;
    }
    
    .cards {
        padding: 0 16px 60px;
        gap: 18px;
    }
    
    .card {
        border-width: 1.5px;
    }
    
    .icon-container {
        height: 160px;
        padding: 0 16px;
    }
    
    .card-content {
        padding: 22px 18px;
    }
    
    .card-content p {
        font-size: 15px;
    }
    
    .card-subtitle {
        font-size: 12px;
    }
}
    </style>
</head>
<body>

    <div class="hero">
        <div class="hero-content">
            <h1>LAYANAN KESEHATAN DOKTER ARIF RAHMAN Sp.PD</h1>
            <p>
                Dapatkan layanan kesehatan lengkap untuk Anda dan keluarga bersama dr. Arif Rahman. 
                Mulai dari <strong>Poliklinik Penyakit Dalam</strong> untuk pemeriksaan menyeluruh, 
                <strong>Terapi Stemcell</strong> untuk membantu regenerasi sel tubuh, 
                <strong>Home Care</strong> profesional yang nyaman di rumah Anda, 
                hingga <strong>Telekonsultasi</strong> praktis yang bisa diakses kapan saja, di mana saja.
            </p>
        </div>
    </div>

    <div class="section-title">
        <h2>Layanan Medis Penyakit Dalam Yang Ditawarkan</h2>
    </div>

    <div class="cards">
        <div class="card">
            <div class="icon-container">
                <img src="assets/images/poliklinik.jpeg" alt="Poli Klinik Penyakit Dalam">
            </div>
            <div class="card-content">
                <p>Poli Klinik Spesialis<br>Penyakit Dalam</p>
            </div>
        </div>

        <div class="card">
            <div class="icon-container">
                <img src="assets/images/stemcell.jpeg" alt="Terapi Regeneratif">
            </div>
            <div class="card-content">
                <p>Terapi Regeneratif<br>(Exosome & Secretome)</p>
            </div>
        </div>

        <div class="card">
            <div class="icon-container">
                <img src="assets/images/homecare.jpeg" alt="Layanan Home Care">
            </div>
            <div class="card-content">
                <p>Home Care<br>(Via Rumah Sakit UNIMUS)</p>
            </div>
        </div>

        <div class="card">
            <div class="icon-container">
                <img src="assets/images/telekonsultasi.jpeg" alt="Telekonsultasi Online">
            </div>
            <div class="card-content">
                <p>Telekonsultasi<br>(Via Humas RS UNIMUS)</p>
            </div>
        </div>
    </div>
<div class="trust-section">
        <div class="trust-badge">
            <div class="trust-badge-icon">✓</div>
            <span class="trust-badge-text">Layanan Terpercaya & Profesional</span>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>