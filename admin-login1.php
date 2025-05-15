<?php
require_once 'db_config.php';

$username = 'admin';
$password = password_hash('Admin@123', PASSWORD_DEFAULT);
$full_name = 'Super Admin';

$stmt = $conn->prepare("INSERT INTO admins (username, password, full_name) VALUES (:username, :password, :full_name)");
$stmt->bindValue(':username', $username);
$stmt->bindValue(':password', $password);
$stmt->bindValue(':full_name', $full_name);
$stmt->execute();

echo "Admin inserted successfully.";
