<?php
session_start();

// Database configuration
class Database {
    private $host = 'localhost';
    private $db_name = 'medical_website';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
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

$error_message = '';

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $database = new Database();
        $db = $database->getConnection();

        $stmt = $db->prepare("SELECT id, username, password, nama FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_nama'] = $admin['nama'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = 'Username atau password salah!';
        }
    } else {
        $error_message = 'Harap isi semua field!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Medical Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .alert {
            border-radius: 15px;
            border: none;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 25px 0 0 25px;
            color: #6c757d;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 25px 25px 0;
        }

        .floating-icons {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .floating-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .floating-icon:nth-child(1) { top: 10%; left: 10%; font-size: 2rem; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 20%; right: 10%; font-size: 1.5rem; animation-delay: 1s; }
        .floating-icon:nth-child(3) { top: 60%; left: 5%; font-size: 2.5rem; animation-delay: 2s; }
        .floating-icon:nth-child(4) { top: 70%; right: 15%; font-size: 1.8rem; animation-delay: 3s; }
        .floating-icon:nth-child(5) { bottom: 10%; left: 20%; font-size: 2rem; animation-delay: 4s; }
        .floating-icon:nth-child(6) { bottom: 20%; right: 5%; font-size: 1.6rem; animation-delay: 5s; }
    </style>
</head>

<body>
    <div class="floating-icons">
        <i class="fas fa-stethoscope floating-icon"></i>
        <i class="fas fa-heartbeat floating-icon"></i>
        <i class="fas fa-user-md floating-icon"></i>
        <i class="fas fa-hospital floating-icon"></i>
        <i class="fas fa-pills floating-icon"></i>
        <i class="fas fa-syringe floating-icon"></i>
    </div>

    <div class="login-card position-relative z-1">
        <div class="login-header">
            <h3><i class="fas fa-user-shield me-2"></i>Admin Login</h3>
            <p class="mb-0">Medical Website Dashboard</p>
        </div>

        <div class="login-body">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="username">
                </div>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="current-password">
                </div>

                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                    </button>
                </div>
            </form>

            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Akses terbatas untuk admin
                </small>
            </div>
        </div>
    </div>

    <script>
        // Animasi masuk
        document.addEventListener('DOMContentLoaded', function () {
            const loginCard = document.querySelector('.login-card');
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(30px)';
            setTimeout(() => {
                loginCard.style.transition = 'all 0.6s ease';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
