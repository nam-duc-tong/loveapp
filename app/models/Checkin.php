<?php
class Checkin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrUpdate($couple_id, $user_id, $mood, $note = '') {
        $today = date('Y-m-d');
        $stmt = $this->conn->prepare(
            "INSERT INTO checkins (couple_id, user_id, mood, note, checkin_date) 
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE mood = VALUES(mood), note = VALUES(note)"
        );
        return $stmt->execute([$couple_id, $user_id, $mood, $note, $today]);
    }

    public function getTodayByCouple($couple_id) {
        $today = date('Y-m-d');
        $stmt = $this->conn->prepare(
            "SELECT c.*, u.display_name, u.avatar_url 
             FROM checkins c 
             JOIN users u ON c.user_id = u.id 
             WHERE c.couple_id = ? AND c.checkin_date = ?"
        );
        $stmt->execute([$couple_id, $today]);
        return $stmt->fetchAll();
    }
}
?>