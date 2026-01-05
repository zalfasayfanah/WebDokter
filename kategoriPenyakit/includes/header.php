<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Web Dokter - <?php echo isset($dokter) ? $dokter['nama'] : 'dr. Arif Rahman'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            overflow-x: hidden;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #1a3c92;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        .navbar .logo {
            font-weight: bold;
            background-color: #f7c948;
            padding: 10px 24px;
            border-radius: 25px;
            color: #1a237e;
            font-size: 1.1rem;
            white-space: nowrap;
            z-index: 1001;
        }
        
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        
        .navbar ul li a {
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: block;
        }
        
        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #f7c948;
            color: #1a237e;
        }

        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
            background: transparent;
            border: none;
        }

        .menu-toggle span {
            width: 30px;
            height: 3px;
            background: #f7c948;
            border-radius: 3px;
            transition: all 0.3s ease;
            display: block;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(8px, -8px);
        }

        /* Tablet */
        @media screen and (max-width: 992px) {
            .navbar {
                padding: 18px 30px;
            }

            .navbar .logo {
                font-size: 1rem;
                padding: 9px 20px;
            }

            .navbar ul {
                gap: 15px;
            }

            .navbar ul li a {
                padding: 8px 14px;
                font-size: 0.95rem;
            }
        }

        /* Mobile */
        @media screen and (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }

            .navbar .logo {
                font-size: 0.95rem;
                padding: 8px 18px;
            }

            .menu-toggle {
                display: flex;
            }

            .navbar ul {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                height: 100vh;
                background: linear-gradient(180deg, #1a3c92 0%, #0d2459 100%);
                flex-direction: column;
                gap: 0;
                padding: 80px 0 20px 0;
                transition: left 0.4s ease;
                box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
                overflow-y: auto;
            }

            .navbar ul.active {
                left: 0;
            }

            .navbar ul li {
                width: 100%;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .navbar ul li a {
                display: block;
                padding: 18px 25px;
                border-radius: 0;
                width: 100%;
                font-size: 1rem;
            }

            .navbar ul li a:hover {
                background-color: rgba(247, 201, 72, 0.15);
                color: #f7c948;
                padding-left: 35px;
            }

            /* Overlay */
            .navbar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                backdrop-filter: blur(3px);
            }

            .navbar-overlay.active {
                display: block;
            }
        }

        /* Small Mobile */
        @media screen and (max-width: 480px) {
            .navbar {
                padding: 12px 15px;
            }

            .navbar .logo {
                font-size: 0.85rem;
                padding: 7px 15px;
            }

            .menu-toggle span {
                width: 26px;
                height: 2.5px;
            }

            .navbar ul {
                width: 260px;
            }

            .navbar ul li a {
                padding: 16px 20px;
                font-size: 0.95rem;
            }
        }

        /* Extra Small Mobile */
        @media screen and (max-width: 360px) {
            .navbar {
                padding: 10px 12px;
            }

            .navbar .logo {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .menu-toggle span {
                width: 24px;
            }

            .navbar ul {
                width: 240px;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay for mobile menu -->
    <div class="navbar-overlay" id="navbarOverlay"></div>

    <nav class="navbar">
        <div class="logo">dr. Arif Rahman, Sp.PD</div>
        
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul id="navMenu">
            <li><a href="../Index.php">Beranda</a></li>
            <li><a href="../Index.php#jadwal">Jadwal Praktek</a></li>
            <li><a href="../Penyakit/penyakit_home.php">Penyakit Dalam</a></li>
            <li><a href="../Pelayanan.php">Pelayanan</a></li>
        </ul>
    </nav>

    <script>
        // Toggle menu mobile
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        const navbarOverlay = document.getElementById('navbarOverlay');

        function toggleMenu() {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
            navbarOverlay.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (navMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        menuToggle.addEventListener('click', toggleMenu);

        // Close menu when clicking overlay
        navbarOverlay.addEventListener('click', function() {
            toggleMenu();
        });

        // Close menu when clicking a link
        document.querySelectorAll('.navbar ul li a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        // Close menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                navbarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Highlight active page
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.navbar ul li a').forEach(link => {
            const linkPage = link.getAttribute('href').split('/').pop().split('#')[0];
            if (linkPage === currentPage || (currentPage === '' && linkPage === 'Index.php')) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>