<?php
class Story {
    private $conn;
    private $table = "stories";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($couple_id, $user_id, $content, $image_url = null, $bg_color = '#FF4D79') {
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (couple_id, user_id, content, image_url, background_color, expires_at) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$couple_id, $user_id, $content, $image_url, $bg_color, $expires_at]);
    }

    public function getActiveByCouple($couple_id) {
        $stmt = $this->conn->prepare(
            "SELECT s.*, u.display_name, u.avatar_url 
             FROM {$this->table} s
             JOIN users u ON s.user_id = u.id
             WHERE s.couple_id = ? AND s.expires_at > NOW()
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([$couple_id]);
        return $stmt->fetchAll();
    }

    public function getByUser($couple_id, $user_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} 
             WHERE couple_id = ? AND user_id = ? AND expires_at > NOW()
             ORDER BY created_at DESC"
        );
        $stmt->execute([$couple_id, $user_id]);
        return $stmt->fetchAll();
    }

    public function deleteExpired() {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE expires_at < NOW()");
        return $stmt->execute();
    }
}
?>