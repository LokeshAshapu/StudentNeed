<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "student_need";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM uploads ORDER BY uploaded_at DESC";
$result = $conn->query($sql);

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        $file_id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM uploads WHERE id = ?");
        $stmt->bind_param("i", $file_id);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Unauthorized access.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Uploaded Files | Student Need</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
    <script src="scripts/main.js" defer></script>
    <style>
        table {
            width: 90%;
            margin: 50px auto;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #aaa;
        }

        th {
            background-color: #f2f2f2;
        }

        .download-btn {
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .download-btn:hover {
            background-color: #218838;
        }

        h1 {
            text-align: center;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-container container">
                <input type="checkbox" name="" id="">
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <ul class="menu-items">
                    <li><a href="index1.html">Upload</a></li>
                    <li><a href="./materials.php">Materials</a></li>
                    <li><a href="./contact.html">Contact</a></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                </ul>
                <h1 class="logo" style="color: black; align-items: center;"><a href="index1.html">Student Needs</a></h1>
            </div>
        </nav>
    </header>

    <h1 style="padding-top: 100px;">Uploaded Materials</h1>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Semester</th>
                <th>Branch</th>
                <th>Subject</th>
                <th>File</th>
                <th>Download</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['year']) ?></td>
                        <td><?= htmlspecialchars($row['semester']) ?></td>
                        <td><?= htmlspecialchars($row['branch']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['filename']) ?></td>
                        <td>
                            <a href="download.php?file=<?= urlencode($row['filename']); ?>" class="download-btn">Download</a>
                        </td>
                        <td>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <a href="dashboard.php?action=delete&id=<?= $row['id']; ?>"
                                    onclick="return confirm('Delete this file?');" class="download-btn">
                                    Delete
                                </a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No files uploaded yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script>
        const supabaseUrl = "https://snhqgaxxzccpqbfmjedm.supabase.co";
        const supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InNuaHFnYXh4emNjcHFiZm1qZWRtIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDg2ODMyMzksImV4cCI6MjA2NDI1OTIzOX0.QykBV1hJH5y0cUcm6Xnb6wtxKaKzq6jUOZudMzGLwEY";
        const supabase = supabase.createClient(supabaseUrl, supabaseKey);
        document.addEventListener("DOMContentLoaded", async () => {
            const {
                data: files,
                error
            } = await supabase
                .from("uploads")
                .select("*")
                .order("uploaded_at", {
                    ascending: false
                });

            if (error) {
                alert("Failed to load files: " + error.message);
                return;
            }

            const table = document.getElementById("fileTable");

            if (files.length === 0) {
                table.innerHTML = `<tr><td colspan="6">No files uploaded yet.</td></tr>`;
                return;
            }

            files.forEach(file => {
                const row = document.createElement("tr");
                row.innerHTML = `
          <td>${file.year}</td>
          <td>${file.semester}</td>
          <td>${file.branch}</td>
          <td>${file.subject}</td>
          <td>${file.filename}</td>
          <td><a href="${file.file_url}" class="download-btn" target="_blank">Download</a></td>
        `;
                table.appendChild(row);
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>