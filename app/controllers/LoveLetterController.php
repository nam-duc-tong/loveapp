<?php
require_once __DIR__ . '/../models/LoveLetter.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class LoveLetterController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $letterModel = new LoveLetter($this->db);
        $letters = $letterModel->getByCouple($user['couple_id']);

        require_once __DIR__ . '/../views/love_letters.php';
    }

    public function create() {
        header('Content-Type: application/json');
        if (!is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ']);
            return;
        }

        $letterModel = new LoveLetter($this->db);
        $result = $letterModel->create($user['couple_id'], $user['id'], $title, $content);

        echo json_encode(['success' => $result]);
    }

    public function read() {
        header('Content-Type: application/json');
        if (!is_logged_in()) {
            echo json_encode(['success' => false]);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $letterModel = new LoveLetter($this->db);
        $letterModel->markAsRead($id, current_user_id());

        echo json_encode(['success' => true]);
    }
}
?>