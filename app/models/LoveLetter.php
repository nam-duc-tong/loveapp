<?php
class LoveLetter
{
    private $conn;
    private $table = "love_letters";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($couple_id, $sender_id, $title, $content)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (couple_id, sender_id, title, content) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$couple_id, $sender_id, $title, $content]);
    }

    public function getByCouple($couple_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT l.*, u.display_name, u.avatar_url 
             FROM {$this->table} l 
             JOIN users u ON l.sender_id = u.id 
             WHERE l.couple_id = ? 
             ORDER BY l.created_at DESC"
        );
        $stmt->execute([$couple_id]);
        return $stmt->fetchAll();
    }

    public function markAsRead($id, $user_id)
    {
        // Chỉ đánh dấu đã đọc nếu người đọc KHÔNG phải người gửi
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET is_read = 1 WHERE id = ? AND sender_id != ?"
        );
        return $stmt->execute([$id, $user_id]);
    }

    public function countUnread($couple_id, $user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as total FROM {$this->table} 
         WHERE couple_id = ? AND sender_id != ? AND is_read = 0"
        );
        $stmt->execute([$couple_id, $user_id]);
        return $stmt->fetch()['total'];
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
