<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "student_need";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $name = $_POST['name'];
// $email = $_POST['email'];
$year = $_POST['year'];
$semester = $_POST['semester'];
$branch = $_POST['branch'];
$subject = $_POST['subject'];

$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}
$filename = basename($_FILES["file"]["name"]);
$targetFile = $targetDir . $filename;

if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    //$stmt = $conn->prepare("INSERT INTO uploads (name, email, year, semester, branch, subject, filename) VALUES (?, ?, ?, ?, ?, ?, ?)");
    //$stmt->bind_param("ssiisss", $name, $email, $year, $semester, $branch, $subject, $filename);
    $stmt = $conn->prepare("INSERT INTO uploads ( year, semester, branch, subject, filename) VALUES ( ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $year, $semester, $branch, $subject, $filename);
    $stmt->execute();
    echo "<script>alert('File uploaded successfully.');
            window.location.href='index.html';</script>";
    // header("Location: index.html");
    
} else {
    echo "<script>alert('Error uploading file.');</script>";
}

$conn->close();
?>
