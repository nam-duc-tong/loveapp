<?php
// hash.php - Dùng để tạo password hash
$password = '123456';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Password: " . $password . "<br>";
echo "Hash: " . $hash . "<br>";
echo "<br>Copy hash này vào câu SQL INSERT:";
echo "<br><br>INSERT INTO users (email, password_hash, display_name, pair_code) VALUES ";
echo "<br>('admin@lovex.com', '" . $hash . "', 'Super Admin', 'ADMIN1');";
?>