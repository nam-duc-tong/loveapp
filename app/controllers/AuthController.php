<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Couple.php';
require_once __DIR__ . '/../../config/database.php';

class AuthController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ Đổi từ email → username
            $username = strtolower(trim($_POST['username'] ?? ''));
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            } else {
                $userModel = new User($this->db);
                $user = $userModel->findByUsername($username);

                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['display_name'] = $user['display_name'];
                    $_SESSION['username'] = $user['username'];
                    redirect(base_url('index.php?action=home'));
                } else {
                    $error = "Biệt danh hoặc mật khẩu không đúng!";
                }
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = strtolower(trim($_POST['username'] ?? ''));
            $password = $_POST['password'] ?? '';
            $display_name = trim($_POST['display_name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            // ✅ Validate username
            if (strlen($username) < 3 || strlen($username) > 20) {
                $error = "Biệt danh phải từ 3-20 ký tự!";
            } elseif (!preg_match('/^[a-z0-9_]+$/', $username)) {
                $error = "Biệt danh chỉ được chứa chữ thường, số và dấu gạch dưới!";
            } elseif (strlen($password) < 6) {
                $error = "Mật khẩu phải từ 6 ký tự!";
            } elseif (empty($display_name)) {
                $error = "Vui lòng nhập tên hiển thị!";
            } else {
                $userModel = new User($this->db);

                // Kiểm tra trùng username
                if ($userModel->findByUsername($username)) {
                    $error = "Biệt danh này đã có người dùng. Chọn biệt danh khác nhé!";
                }
                // Kiểm tra trùng email (nếu có nhập)
                elseif (!empty($email) && $userModel->findByEmail($email)) {
                    $error = "Email này đã được sử dụng!";
                } else {
                    // Tạo user mới
                    $user_id = $userModel->create($username, $password, $display_name, $email ?: null);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['display_name'] = $display_name;
                    $_SESSION['username'] = $username;
                    redirect(base_url('index.php?action=home'));
                }
            }
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout()
    {
        // Xóa session
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        // ✅ Chỉ require view, KHÔNG cần header/footer
        require_once __DIR__ . '/../views/auth/goodbye.php';
        exit; // ✅ Thêm exit để không chạy code sau
    }

    public function pair()
    {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        // Nếu user đã có couple đang active → chuyển về home
        if ($user['couple_id']) {
            $coupleModel = new Couple($this->db);
            $couple = $coupleModel->findById($user['couple_id']);
            if ($couple && $couple['status'] === 'active' && $couple['user_2_id']) {
                redirect(base_url('index.php?action=home'));
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['pair_action'] ?? '';
            $start_date = $_POST['start_date'] ?? date('Y-m-d');

            if ($action === 'create') {
                $coupleModel = new Couple($this->db);

                if ($user['couple_id']) {
                    $error = "Bạn đã có cặp đôi rồi!";
                } else {
                    $couple_id = $coupleModel->create($user['id'], $start_date);
                    $userModel->updateCoupleId($user['id'], $couple_id);
                    // Reload user để có couple_id mới
                    $user = $userModel->findById(current_user_id());
                    $success = "Đã tạo cặp đôi! Hãy gửi mã <strong>" . $user['pair_code'] . "</strong> cho người ấy.";
                }
            } elseif ($action === 'join') {
                $code = strtoupper(trim($_POST['pair_code'] ?? ''));
                $partner = $userModel->findByPairCode($code);

                if (!$partner || $partner['id'] == $user['id']) {
                    $error = "Mã ghép đôi không hợp lệ!";
                } elseif ($partner['couple_id']) {
                    // Partner đã có couple → kiểm tra xem couple đó có trống user_2 không
                    $coupleModel = new Couple($this->db);
                    $partner_couple = $coupleModel->findById($partner['couple_id']);

                    if ($partner_couple && !$partner_couple['user_2_id']) {
                        // Tham gia vào couple của partner
                        $coupleModel->activate($partner_couple['id'], $user['id'], $start_date);
                        $userModel->updateCoupleId($user['id'], $partner_couple['id']);
                        redirect(base_url('index.php?action=home'));
                    } else {
                        $error = "Người này đã có cặp đôi!";
                    }
                } else {
                    // Partner chưa có couple → tạo mới cho cả 2
                    $coupleModel = new Couple($this->db);
                    $couple_id = $coupleModel->create($partner['id'], $start_date);
                    $coupleModel->activate($couple_id, $user['id'], $start_date);
                    $userModel->updateCoupleId($partner['id'], $couple_id);
                    $userModel->updateCoupleId($user['id'], $couple_id);
                    redirect(base_url('index.php?action=home'));
                }
            }
        }

        // ✅ LUÔN truyền $user sang view
        require_once __DIR__ . '/../views/auth/pair.php';
    }
}
