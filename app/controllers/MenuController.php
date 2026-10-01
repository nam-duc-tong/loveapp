<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Couple.php';
require_once __DIR__ . '/../../config/database.php';

class MenuController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // public function settings()
    // {
    //     if (!is_logged_in()) redirect(base_url('index.php?action=login'));

    //     $userModel = new User($this->db);
    //     $user = $userModel->findById(current_user_id());

    //     // ✅ BẮT BUỘC kiểm tra user tồn tại
    //     if (!$user) {
    //         session_destroy();
    //         redirect(base_url('index.php?action=login'));
    //     }

    //     $coupleModel = new Couple($this->db);
    //     $couple = null;
    //     if (!empty($user['couple_id'])) {
    //         $couple = $coupleModel->findById($user['couple_id']);
    //     }

    //     // ✅ Truyền cả $user và $couple sang view
    //     require_once __DIR__ . '/../views/settings.php';
    // }
    // public function settings()
    // {
    //     if (!is_logged_in()) redirect(base_url('index.php?action=login'));

    //     $userModel = new User($this->db);
    //     $user = $userModel->findById(current_user_id());

    //     $coupleModel = new Couple($this->db);
    //     $couple = $user['couple_id'] ? $coupleModel->findById($user['couple_id']) : null;

    //     require_once __DIR__ . '/../views/settings.php';
    // }

      public function settings() {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        // ✅ Nếu user không tồn tại (bị xóa khỏi DB) → force logout
        if (!$user) {
            session_destroy();
            redirect(base_url('index.php?action=login'));
        }

        $coupleModel = new Couple($this->db);
        $couple = null;
        if (!empty($user['couple_id'])) {
            $couple = $coupleModel->findById($user['couple_id']);
        }

        // ✅ Truyền $user và $couple sang view
        require_once __DIR__ . '/../views/settings.php';
    }
    public function updateProfile()
    {
        header('Content-Type: application/json');
        if (!is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $display_name = trim($_POST['display_name'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $birthday = $_POST['birthday'] ?? null;
        $avatar_url = null;

        if (empty($display_name)) {
            echo json_encode(['success' => false, 'message' => 'Tên không được để trống']);
            return;
        }

        // Upload avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $allowed)) {
                $filename = uniqid('avatar_') . '.' . $ext;
                $target = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
                    $avatar_url = 'uploads/avatars/' . $filename;
                }
            }
        }

        $userModel = new User($this->db);
        $result = $userModel->updateProfile(current_user_id(), $display_name, $bio, $birthday, $avatar_url);

        // Cập nhật session
        $_SESSION['display_name'] = $display_name;

        echo json_encode(['success' => $result]);
    }

    public function updateStartDate()
    {
        header('Content-Type: application/json');
        if (!is_logged_in()) {
            echo json_encode(['success' => false]);
            return;
        }

        $start_date = $_POST['start_date'] ?? null;
        if (!$start_date) {
            echo json_encode(['success' => false, 'message' => 'Chưa chọn ngày']);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $coupleModel = new Couple($this->db);
        $result = $coupleModel->updateStartDate($user['couple_id'], $start_date);

        echo json_encode(['success' => $result]);
    }
}
