<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: text/plain; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$db = "student_need";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!$full_name || !$email || !$password) {
        http_response_code(400);
        echo "Missing required fields.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format.";
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $checkSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        http_response_code(409);
        echo "Email already registered.";
        $checkStmt->close();
        $conn->close();
        exit();
    }
    $checkStmt->close();

    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $full_name, $email, $password_hash);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "User registered successfully.";
    } else {
        http_response_code(500);
        echo "Database insert error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
