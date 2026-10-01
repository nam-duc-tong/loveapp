<?php
// config/database.php
class Database
{
    private $host = "localhost";
    private $db_name = "lovex_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Lỗi kết nối Database: " . $e->getMessage());
        }
        return $this->conn;
    }
}

// Khởi động session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hàm helper
function base_url($path = '')
{
    return "/loveapp/public/" . ltrim($path, '/');
}

function redirect($url)
{
    header("Location: " . $url);
    exit;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}
// ============ CSRF PROTECTION ============
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf()
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('CSRF token không hợp lệ. Vui lòng tải lại trang.');
    }
}
