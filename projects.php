<?php
require 'session.php';
require 'db.php';

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM projects WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Projects</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .top-actions a {
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .top-actions a:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
        }

        td a {
            color: #007BFF;
            text-decoration: none;
            margin-right: 10px;
        }

        td a:hover {
            text-decoration: underline;
        }

        .no-projects {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Projects</h2>

    <div class="top-actions">
        <a href="create_project.php">+ Create Project</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <a href="edit_project.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="delete_project.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this project?')">Delete</a>
                        <a href="tasks.php?project_id=<?= $row['id'] ?>">View Tasks</a>
                        <a href="project_report.php?project_id=<?= $row['id'] ?>">Report</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php else: ?>
        <div class="no-projects">You don't have any projects yet.</div>
    <?php endif; ?>
</div>

</body>
</html>
