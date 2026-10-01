<?php
class Timeline {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTimeline($couple_id) {
        $events = [];

        // Lấy memories
        $stmt = $this->conn->prepare(
            "SELECT 'memory' as type, id, title as title, description as description, 
                    event_date as date, image_url, created_by as user_id, created_at
             FROM memories WHERE couple_id = ?"
        );
        $stmt->execute([$couple_id]);
        $events = array_merge($events, $stmt->fetchAll());

        // Lấy love letters
        $stmt = $this->conn->prepare(
            "SELECT 'letter' as type, id, title, content as description, 
                    DATE(created_at) as date, NULL as image_url, sender_id as user_id, created_at
             FROM love_letters WHERE couple_id = ?"
        );
        $stmt->execute([$couple_id]);
        $events = array_merge($events, $stmt->fetchAll());

        // Sắp xếp theo ngày mới nhất
        usort($events, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $events;
    }
}
?>