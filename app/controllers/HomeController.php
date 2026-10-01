<?php
// ✅ Truyền trạng thái onboarding
$GLOBALS['user_onboarding_done'] = $user['onboarding_done'] ?? 1;
require_once __DIR__ . '/../models/Couple.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Checkin.php';
require_once __DIR__ . '/../models/Memory.php';
require_once __DIR__ . '/../models/LoveLetter.php';
require_once __DIR__ . '/../../config/database.php';

class HomeController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index()
    {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        // Nếu chưa ghép đôi → chuyển đến trang pair
        if (!$user['couple_id']) {
            redirect(base_url('index.php?action=pair'));
        }

        $coupleModel = new Couple($this->db);
        $couple = $coupleModel->findById($user['couple_id']);

        if (!$couple || !$couple['user_2_id']) {
            redirect(base_url('index.php?action=pair'));
        }

        // ===== Dữ liệu hiển thị =====
        $daysTogether   = $coupleModel->getDaysTogether($couple['start_date']);
        $monthsTogether = $coupleModel->getMonthsTogether($couple['start_date']);
        $startDate      = $couple['start_date'];

        // Partner info
        $partner = $coupleModel->getPartner($couple['id'], current_user_id());

        // Đếm kỷ niệm
        $memoryModel  = new Memory($this->db);
        $memoryCount  = $memoryModel->countByCouple($couple['id']);

        // Đếm ảnh (đếm memory có image_url)
        $photoCount = 0;
        $allMemories = $memoryModel->getByCouple($couple['id']);
        foreach ($allMemories as $m) {
            if (!empty($m['image_url'])) $photoCount++;
        }

        // Đếm thư chưa đọc
        $letterModel   = new LoveLetter($this->db);
        $unreadLetters = $letterModel->countUnread($couple['id'], current_user_id());

        // Check-in hôm nay
        $checkinModel  = new Checkin($this->db);
        $todayCheckins = $checkinModel->getTodayByCouple($couple['id']);

        // Tìm check-in của mình và partner
        $myCheckin      = null;
        $partnerCheckin = null;
        foreach ($todayCheckins as $c) {
            if ($c['user_id'] == current_user_id()) $myCheckin = $c;
            if ($partner && $c['user_id'] == $partner['id']) $partnerCheckin = $c;
        }

        require_once __DIR__ . '/../views/home.php';
    }
    public function help()
    {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));
        require_once __DIR__ . '/../views/help.php';
    }

    public function onboardingDone()
    {
        header('Content-Type: application/json');
        if (!is_logged_in()) {
            echo json_encode(['success' => false]);
            return;
        }

        $stmt = $this->db->prepare("UPDATE users SET onboarding_done = 1 WHERE id = ?");
        $stmt->execute([current_user_id()]);

        echo json_encode(['success' => true]);
    }
}
