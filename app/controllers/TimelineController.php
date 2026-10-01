<?php
require_once __DIR__ . '/../models/Timeline.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

class TimelineController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        if (!is_logged_in()) redirect(base_url('index.php?action=login'));

        $userModel = new User($this->db);
        $user = $userModel->findById(current_user_id());

        if (!$user['couple_id']) redirect(base_url('index.php?action=pair'));

        $timelineModel = new Timeline($this->db);
        $events = $timelineModel->getTimeline($user['couple_id']);

        require_once __DIR__ . '/../views/timeline.php';
    }
}
?>