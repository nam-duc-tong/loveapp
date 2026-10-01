<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ✅ Tìm theo username (dùng để đăng nhập)
    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // Vẫn giữ để kiểm tra trùng email (nếu có)
    public function findByEmail($email) {
        if (empty($email)) return false;
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByPairCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE pair_code = ? LIMIT 1");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    // ✅ Đăng ký với username
    public function create($username, $password, $display_name, $email = null) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pair_code = strtoupper(substr(md5(uniqid()), 0, 6));
        
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (username, email, password_hash, display_name, pair_code) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$username, $email, $hash, $display_name, $pair_code]);
        return $this->conn->lastInsertId();
    }

    public function updateCoupleId($user_id, $couple_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET couple_id = ? WHERE id = ?");
        return $stmt->execute([$couple_id, $user_id]);
    }

    public function updateProfile($user_id, $display_name, $bio = null, $birthday = null, $avatar_url = null) {
        if ($avatar_url) {
            $stmt = $this->conn->prepare(
                "UPDATE {$this->table} 
                 SET display_name = ?, bio = ?, birthday = ?, avatar_url = ? 
                 WHERE id = ?"
            );
            return $stmt->execute([$display_name, $bio, $birthday, $avatar_url, $user_id]);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE {$this->table} 
                 SET display_name = ?, bio = ?, birthday = ? 
                 WHERE id = ?"
            );
            return $stmt->execute([$display_name, $bio, $birthday, $user_id]);
        }
    }
}
?>