<?php
require_once __DIR__ . '/../models/Checkin.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class CheckinController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function save()
    {
        header('Content-Type: application/json');

        if (!is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        $mood = $_POST['mood'] ?? 'normal';
        $note = $_POST['note'] ?? '';

        $checkinModel = new Checkin($this->db);
        $result = $checkinModel->createOrUpdate($user['couple_id'], $user['id'], $mood, $note);

        echo json_encode(['success' => $result]);
    }
    public function stats()
    {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        // Lấy 30 ngày gần nhất
        $stmt = $this->db->prepare(
            "SELECT mood, checkin_date FROM checkins 
         WHERE couple_id = ? AND checkin_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
         ORDER BY checkin_date ASC"
        );
        $stmt->execute([$user['couple_id']]);
        $stats = $stmt->fetchAll();

        // Đếm số lần mỗi mood
        $moodCount = ['happy' => 0, 'love' => 0, 'normal' => 0, 'tired' => 0, 'sad' => 0, 'angry' => 0];
        foreach ($stats as $s) {
            if (isset($moodCount[$s['mood']])) $moodCount[$s['mood']]++;
        }

        require_once __DIR__ . '/../views/emotions.php';
    }
}
