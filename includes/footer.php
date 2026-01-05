<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Footer Dokter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<footer class="footer">
    <div class="footer-curve"></div>
    <div class="footer-content">
        <div class="footer-section">
            <h3 class="footer-title">Dokter Spesialis Penyakit Dalam</h3>
            <p class="footer-slogan">Melayani dengan sepenuh hati untuk kesehatan anda</p>
            <div class="footer-social-links">
                <a href="" class="footer-social-link">
                    <i class="fa-brands fa-facebook-f"></i>
                    <span>@doktermimin</span>
                </a>
                <a href="https://www.instagram.com/doktermimin?igsh=cW5zZmprM25odDVi" class="footer-social-link" target="_blank" rel="noopener">
                    <i class="fa-brands fa-instagram"></i>
                    <span>@doktermimin</span>
                </a>
                <a href="https://www.tiktok.com/@user900289442?_r=1&_t=ZS-91T49nJBKqe" class="footer-social-link" target="_blank" rel="noopener">
                    <i class="fa-brands fa-tiktok"></i>
                    <span>@simimin</span>
                </a>
            </div>
        </div>
        <div class="footer-section">
            <h3 class="footer-title">Kontak Kami</h3>
            <div class="footer-contact-info">
                <div class="footer-contact-item">
                    <i class="fa-solid fa-map-marker-alt"></i>
                    <span>dr. Arif Rahman Sp.PD, FINASIM, FINEM, AIFO-K, FISQua (Dokter Spesialis Penyakit Dalam & Dokter Stemcell) Kota Semarang</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fa-solid fa-envelope"></i>
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
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
        width: 100%;
    }

    .footer {
        background: #1a3c92;
        color: white;
        margin-top: 4rem;
        position: relative;
        overflow: hidden;
        width: 100%;
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
        gap: 2rem;
        padding: 3rem 2rem 2rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .footer-section {
        flex: 1;
        min-width: 0;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .footer-title {
        color: #f7c948;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
        line-height: 1.3;
        word-wrap: break-word;
    }

    .footer-slogan {
        font-style: italic;
        color: #ccc;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        line-height: 1.5;
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
        transition: all 0.3s ease;
        word-break: break-word;
        -webkit-tap-highlight-color: rgba(247, 201, 72, 0.3);
    }

    .footer-social-link:hover,
    .footer-social-link:active {
        color: #f7c948;
        transform: translateX(5px);
    }

    .footer-social-link i {
        color: #f7c948;
        margin-right: 0.8rem;
        font-size: 1.3rem;
        min-width: 24px;
        flex-shrink: 0;
        text-align: center;
    }

    .footer-contact-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .footer-contact-item {
        display: flex;
        align-items: flex-start;
        color: white;
        line-height: 1.6;
    }

    .footer-contact-item i {
        color: #f7c948;
        margin-right: 0.8rem;
        font-size: 1.3rem;
        min-width: 24px;
        flex-shrink: 0;
        margin-top: 0.2rem;
        text-align: center;
    }

    .footer-contact-item span {
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .footer-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.2);
        margin: 0 2rem;
        width: calc(100% - 4rem);
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .footer-copyright {
        text-align: center;
        padding: 1.5rem 2rem;
        color: white;
        font-size: 0.9rem;
        line-height: 1.5;
        word-wrap: break-word;
    }

    /* Tablet */
    @media screen and (max-width: 992px) {
        .footer-content {
            padding: 2.5rem 1.5rem 1.5rem 1.5rem;
            gap: 1.5rem;
        }

        .footer-title {
            font-size: 1.2rem;
        }

        .footer-slogan {
            font-size: 0.9rem;
        }

        .footer-social-link,
        .footer-contact-item {
            font-size: 0.85rem;
        }

        .footer-divider {
            margin: 0 1.5rem;
            width: calc(100% - 3rem);
        }

        .footer-copyright {
            padding: 1.3rem 1.5rem;
            font-size: 0.8rem;
        }
    }

    /* Mobile - TETAP 2 KOLOM */
    @media screen and (max-width: 768px) {
        .footer {
            margin-top: 2rem;
        }

        .footer-content {
            display: flex;
            flex-direction: row;
            padding: 2rem 1rem 1.5rem 1rem;
            gap: 1rem;
        }

        .footer-section {
            margin: 0;
            width: 50%;
            flex: 1;
        }

        .footer-title {
            font-size: 1rem;
            margin-bottom: 0.7rem;
        }

        .footer-slogan {
            font-size: 0.75rem;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .footer-social-links {
            gap: 0.6rem;
        }

        .footer-social-link {
            font-size: 0.75rem;
            padding: 0.2rem 0;
        }

        .footer-social-link i {
            font-size: 0.95rem;
            min-width: 18px;
            margin-right: 0.5rem;
        }

        .footer-contact-info {
            gap: 0.8rem;
        }

        .footer-contact-item {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .footer-contact-item i {
            font-size: 0.95rem;
            min-width: 18px;
            margin-right: 0.5rem;
        }

        .footer-divider {
            margin: 0 1rem;
            width: calc(100% - 2rem);
        }

        .footer-copyright {
            padding: 1rem;
            font-size: 0.7rem;
        }

        .footer-curve {
            height: 15px;
        }
    }

    /* Small Mobile - TETAP 2 KOLOM LEBIH KECIL */
    @media screen and (max-width: 480px) {
        .footer {
            margin-top: 1.5rem;
        }

        .footer-content {
            padding: 1.5rem 0.7rem 1rem 0.7rem;
            gap: 0.7rem;
        }

        .footer-title {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .footer-slogan {
            font-size: 0.7rem;
            margin-bottom: 0.6rem;
            line-height: 1.3;
        }

        .footer-social-links {
            gap: 0.5rem;
        }

        .footer-social-link,
        .footer-contact-item {
            font-size: 0.7rem;
        }

        .footer-social-link i,
        .footer-contact-item i {
            font-size: 0.85rem;
            margin-right: 0.4rem;
            min-width: 16px;
        }

        .footer-contact-info {
            gap: 0.7rem;
        }

        .footer-divider {
            margin: 0 0.7rem;
            width: calc(100% - 1.4rem);
        }

        .footer-copyright {
            padding: 0.8rem 0.7rem;
            font-size: 0.65rem;
        }

        .footer-curve {
            height: 12px;
            border-radius: 0 0 25px 25px;
        }
    }

    /* Extra Small Mobile - TETAP 2 KOLOM SUPER COMPACT */
    @media screen and (max-width: 360px) {
        .footer-content {
            padding: 1.2rem 0.5rem 0.8rem 0.5rem;
            gap: 0.5rem;
        }

        .footer-title {
            font-size: 0.8rem;
            margin-bottom: 0.4rem;
        }

        .footer-slogan {
            font-size: 0.65rem;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .footer-social-links,
        .footer-contact-info {
            gap: 0.4rem;
        }

        .footer-social-link,
        .footer-contact-item {
            font-size: 0.65rem;
            line-height: 1.3;
        }

        .footer-social-link i,
        .footer-contact-item i {
            font-size: 0.8rem;
            margin-right: 0.3rem;
            min-width: 14px;
        }

        .footer-divider {
            margin: 0 0.5rem;
            width: calc(100% - 1rem);
        }

        .footer-copyright {
            padding: 0.7rem 0.5rem;
            font-size: 0.6rem;
        }

        .footer-curve {
            height: 10px;
        }
    }
</style>

</body>
</html>