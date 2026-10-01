<?php
require_once __DIR__ . '/../models/Story.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class StoryController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

   public function index() {
    if (!is_logged_in()) redirect(base_url('index.php?action=login'));

    $userModel = new User($this->db);
    $user = $userModel->findById(current_user_id());

    if (!$user || !$user['couple_id']) {
        redirect(base_url('index.php?action=pair'));
    }

    // ✅ Lấy thông tin partner
    require_once __DIR__ . '/../models/Couple.php';
    $coupleModel = new Couple($this->db);
    $partner = $coupleModel->getPartner($user['couple_id'], current_user_id());

    // Lấy story
    $storyModel = new Story($this->db);
    $storyModel->deleteExpired();
    $stories = $storyModel->getActiveByCouple($user['couple_id']);

    // Nhóm story theo user
    $grouped = [];
    foreach ($stories as $s) {
        $grouped[$s['user_id']][] = $s;
    }

    require_once __DIR__ . '/../views/story.php';
}

    public function create() {
        header('Content-Type: application/json');
        if (!is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $content = trim($_POST['content'] ?? '');
        $bg_color = $_POST['bg_color'] ?? '#FF4D79';
        $image_url = null;

        // Upload ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/stories/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận ảnh jpg, png, gif, webp']);
                return;
            }

            $filename = uniqid('story_') . '.' . $ext;
            $target = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_url = 'uploads/stories/' . $filename;
            }
        }

        if (empty($content) && !$image_url) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập nội dung hoặc chọn ảnh']);
            return;
        }

        $storyModel = new Story($this->db);
        $result = $storyModel->create($user['couple_id'], $user['id'], $content, $image_url, $bg_color);

        echo json_encode(['success' => $result]);
    }
}
?>