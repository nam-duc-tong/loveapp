<?php
class Memory {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($couple_id, $title, $description, $event_date, $image_url, $user_id) {
        $stmt = $this->conn->prepare(
            "INSERT INTO memories (couple_id, title, description, event_date, image_url, created_by) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$couple_id, $title, $description, $event_date, $image_url, $user_id]);
    }

    public function getByCouple($couple_id) {
        $stmt = $this->conn->prepare(
            "SELECT m.*, u.display_name FROM memories m 
             JOIN users u ON m.created_by = u.id 
             WHERE m.couple_id = ? ORDER BY m.event_date DESC"
        );
        $stmt->execute([$couple_id]);
        return $stmt->fetchAll();
    }

    public function countByCouple($couple_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM memories WHERE couple_id = ?");
        $stmt->execute([$couple_id]);
        return $stmt->fetch()['total'];
    }
}
?>