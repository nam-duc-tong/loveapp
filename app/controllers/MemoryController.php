<?php
require_once __DIR__ . '/../models/Memory.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class MemoryController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $memoryModel = new Memory($this->db);
        $memories = $memoryModel->getByCouple($user['couple_id']);

        require_once __DIR__ . '/../views/memories.php';
    }

    public function create() {
        header('Content-Type: application/json');

        if (!is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $event_date = $_POST['event_date'] ?? date('Y-m-d');
        $image_url = null;

        // Upload ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('mem_') . '.' . $ext;
            $target = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_url = 'uploads/' . $filename;
            }
        }

        $memoryModel = new Memory($this->db);
        $result = $memoryModel->create($user['couple_id'], $title, $description, $event_date, $image_url, $user['id']);

        echo json_encode(['success' => $result]);
    }
}
?>