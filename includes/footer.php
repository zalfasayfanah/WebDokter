<footer class="footer">
    <div class="footer-curve"></div>
    <div class="footer-content">
        <div class="footer-section">
            <h3 class="footer-title">Dokter Spesialis Penyakit Dalam</h3>
            <p class="footer-slogan">Melayani dengan sepenuh hati untuk kesehatan anda</p>
            <div class="footer-social-links">
                <a href="https://facebook.com/dokter" class="footer-social-link">
                    <i class="fab fa-facebook-f"></i>
                    <span>Dokter Arif Rahman</span>
                </a>
                <a href="https://instagram.com/dokter" class="footer-social-link">
                    <i class="fab fa-instagram"></i>
                    <span>@doktermimin</span>
                </a>
                <a href="https://tiktok.com/@dokter" class="footer-social-link">
                    <i class="fab fa-tiktok"></i>
                    <span>@doktermimin</span>
                </a>
               
            </div>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">Kontak Kami</h3>
            <div class="footer-contact-info">
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>dr. Arif Rahman Sp.PD, FINASIM, FINEM, AIFO-K, FISQua (Dokter Spesialis Penyakit Dalam & Dokter Stemcell) Kota Semarang</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>arifrahmanphone@gmail.com</span>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-divider"></div>
    <div class="footer-copyright">
        <p>&copy; 2025 Dokter Arif Rahman, Sp.PD, FINASIM, FINEM, AIFO-K, FISQua. Semua Hak Dilindungi.</p>
    </div>
</footer>

<style>
    .footer {
        background: #1a3c92;
        color: white;
        margin-top: 4rem;
        position: relative;
        overflow: hidden;
    }

    .footer-curve {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(135deg, #1a3c92 0%, #3b82f6 100%);
        border-radius: 0 0 50px 50px;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        padding: 3rem 2rem 2rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-section {
        flex: 1;
        margin: 0 1rem;
    }

    .footer-title {
        color: #f7c948;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
        font-family: 'Arial', sans-serif;
    }

    .footer-slogan {
        font-style: italic;
        color: #ccc;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .footer-social-links {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .footer-social-link {
        display: flex;
        align-items: center;
        color: white;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-social-link:hover {
        color: #f7c948;
    }

    .footer-social-link i {
        color: #f7c948;
        margin-right: 0.8rem;
        font-size: 1.2rem;
        width: 20px;
    }

    .footer-contact-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .footer-contact-item {
        display: flex;
        align-items: center;
        color: white;
    }

    .footer-contact-item i {
        color: #f7c948;
        margin-right: 0.8rem;
        font-size: 1.2rem;
        width: 20px;
    }

    .footer-divider {
        height: 1px;
        background: #555;
        margin: 0 2rem;
    }

    .footer-copyright {
        text-align: center;
        padding: 1.5rem 2rem;
        color: white;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            padding: 2rem 1rem 1rem 1rem;
        }

        .footer-section {
            margin: 0 0 2rem 0;
        }

        .footer-title {
            font-size: 1.3rem;
        }

        .footer-social-links {
            gap: 0.8rem;
        }

        .footer-contact-info {
            gap: 0.8rem;
        }
    }
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
