<?php
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function redirectIfNotAdmin() {
    if (!isAdminLoggedIn()) {
        header("Location: admin-login.html");
        exit();
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>