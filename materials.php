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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Uploaded Files | Student Need</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
    <script>
        const isAdmin = <?= (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'true' : 'false'; ?>;
    </script>
    <style>
        table {
            width: 90%;
            margin: 50px auto;
            border-collapse: collapse;
        }
        th, td {
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

    <table id="fileTable">
        <thead>
            <tr>
                <th>Year</th>
                <th>Semester</th>
                <th>Branch</th>
                <th>Subject</th>
                <th>File</th>
                <th>Download</th>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <th>Delete</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="7">Loading files...</td></tr>
        </tbody>
    </table>

    <script>
        const supabaseUrl = "https://snhqgaxxzccpqbfmjedm.supabase.co";
        const supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InNuaHFnYXh4emNjcHFiZm1qZWRtIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDg2ODMyMzksImV4cCI6MjA2NDI1OTIzOX0.QykBV1hJH5y0cUcm6Xnb6wtxKaKzq6jUOZudMzGLwEY";
        const supabase = supabase.createClient(supabaseUrl, supabaseKey);

        document.addEventListener("DOMContentLoaded", async () => {
            const { data: files, error } = await supabase
                .from("uploads")
                .select("*")
                .order("uploaded_at", { ascending: false });

            const tableBody = document.getElementById("tableBody");

            if (error || !files) {
                tableBody.innerHTML = `<tr><td colspan="7">Error loading files.</td></tr>`;
                return;
            }

            if (files.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="7">No files uploaded yet.</td></tr>`;
                return;
            }

            tableBody.innerHTML = "";

            files.forEach(file => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${file.year}</td>
                    <td>${file.semester}</td>
                    <td>${file.branch}</td>
                    <td>${file.subject}</td>
                    <td>${file.filename}</td>
                    <td><a href="${file.file_url}" class="download-btn" target="_blank">Download</a></td>
                    ${isAdmin ? `<td><button class="download-btn" onclick="deleteFile(${file.id})">Delete</button></td>` : ""}
                `;
                tableBody.appendChild(row);
            });
        });

        async function deleteFile(id) {
            if (!confirm("Are you sure you want to delete this file?")) return;

            const { error } = await supabase
                .from("uploads")
                .delete()
                .eq("id", id);

            if (error) {
                alert("Delete failed: " + error.message);
            } else {
                alert("File deleted successfully.");
                location.reload();
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
