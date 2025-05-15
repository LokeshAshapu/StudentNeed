<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_config.php';

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    header("Location: admin-login.html");
    exit();
}

$materials_query = $conn->query("SELECT COUNT(*) AS total FROM uploads");
if (!$materials_query) {
    $errorInfo = $conn->errorInfo();
    die("Materials query failed: " . $errorInfo[2]);
}
$materials = $materials_query->fetch(PDO::FETCH_ASSOC);

$authors_query = $conn->query("SELECT COUNT(*) AS total FROM admins");
if (!$authors_query) {
    $errorInfo = $conn->errorInfo();
    die("Authors query failed: " . $errorInfo[2]);
}
$authors = $authors_query->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $file_id = intval($_GET['id']);
    $conn->query("DELETE FROM uploads WHERE id = $file_id");
    header("Location: dashboard.php");
    exit();
}


$activities_query = $conn->query("
    SELECT id, uploaded_at AS date, 'material' AS type, subject AS title
    FROM uploads m
    ORDER BY m.uploaded_at DESC
    LIMIT 5
");


if (!$activities_query) {
    $errorInfo = $conn->errorInfo();
    die("Recent activity query failed: " . $errorInfo[2]);
}


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $file_id = intval($_GET['id']);
    $author_id = $_SESSION['admin_id'];

    $check_query = $conn->query("SELECT authors_id FROM materials WHERE id = $file_id");
    if ($check_query && $file = $check_query->fetch(PDO::FETCH_ASSOC)) {
        if ($file['author_id'] == $author_id) {
            $conn->query("DELETE FROM materials WHERE id = $file_id");
            header("Location: dashboard.php");
            exit();
        } else {
            echo "You can only delete your own uploaded files.";
        }
    } else {
        echo "Invalid file or permission denied.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Needs</title>
    <link rel="stylesheet" href="./admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="admin-dashboard">
    <div class="admin-container">
        <div class="sidebar">
            <div class="logo">
                <img src="./logo.png" alt="Student Needs">
                <h2>Student Needs</h2>
            </div>
            <nav>
                <ul>
                    <li class="active"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="materials.php"><i class="fas fa-book"></i> Materials</a></li>
                    <li><a href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>Welcome, Admin</span>
                    <img src="https://ui-avatars.com/api/?name= Admin" alt="Admin">
                </div>
            </header>

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #4e73df;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Materials</h3>
                        <p><?php echo $materials['total']; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #e74a3b;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Registered Authors</h3>
                        <p><?php echo $authors['total']; ?></p>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <h2>Recent Activity</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Activity</th>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User</th>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($activity = $activities_query->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($activity['date'])); ?></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ucfirst($activity['type']); ?></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admin</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($activity['title']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
