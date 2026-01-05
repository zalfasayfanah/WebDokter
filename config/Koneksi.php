<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'medical-website';
    private $username = 'root';
    private $password = 'password'; // kosong untuk XAMPP
    private $port = '3306';

    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Helper functions
function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateAlert($type, $message)
{
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                <i class='fas fa-" . ($type == 'success' ? 'check-circle' : 'exclamation-triangle') . " me-2'></i>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Authentication helper
function checkAdminAuth()
{
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

// CSRF Protection
function generateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
