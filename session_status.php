<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_email'])) {
    echo json_encode(['loggedIn' => true]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
