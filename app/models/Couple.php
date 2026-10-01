<?php
class Couple
{
    private $conn;
    private $table = "couples";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($user_1_id, $start_date)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (user_1_id, start_date, status) VALUES (?, ?, 'pending')"
        );
        $stmt->execute([$user_1_id, $start_date]);
        return $this->conn->lastInsertId();
    }

    public function activate($couple_id, $user_2_id, $start_date)
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET user_2_id = ?, start_date = ?, status = 'active' WHERE id = ?"
        );
        return $stmt->execute([$user_2_id, $start_date, $couple_id]);
    }

    public function getDaysTogether($start_date)
    {
        $start = new DateTime($start_date);
        $now = new DateTime();
        return $now->diff($start)->days;
    }

    public function getMonthsTogether($start_date)
    {
        $start = new DateTime($start_date);
        $now = new DateTime();
        $diff = $now->diff($start);
        return ($diff->y * 12) + $diff->m;
    }

    public function getPartner($couple_id, $current_user_id)
    {
        $couple = $this->findById($couple_id);
        if (!$couple) return null;

        $partner_id = ($couple['user_1_id'] == $current_user_id)
            ? $couple['user_2_id']
            : $couple['user_1_id'];

        if (!$partner_id) return null;

        $stmt = $this->conn->prepare("SELECT id, display_name, avatar_url FROM users WHERE id = ?");
        $stmt->execute([$partner_id]);
        return $stmt->fetch();
    }
    public function updateStartDate($couple_id, $start_date)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET start_date = ? WHERE id = ?");
        return $stmt->execute([$start_date, $couple_id]);
    }
}
