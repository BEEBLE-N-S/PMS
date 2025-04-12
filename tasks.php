<?php
require 'session.php';
require 'db.php';

$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

// Validate access
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

if (!$project) {
    die("Access denied or project not found.");
}

$tasks = $conn->query("SELECT * FROM tasks WHERE project_id = $project_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks - <?= htmlspecialchars($project['name']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions a {
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 10px 18px;
            border-radius: 6px;
            margin: 0 5px;
            transition: background 0.3s;
        }

        .actions a:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f0f0f0;
            color: #555;
        }

        td a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }

        td a:hover {
            text-decoration: underline;
        }

        .status {
            font-weight: bold;
            color: #17a2b8;
        }

        .remark {
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tasks for Project: <?= htmlspecialchars($project['name']) ?></h2>

    <div class="actions">
        <a href="create_task.php?project_id=<?= $project_id ?>">+ Add Task</a>
        <a href="projects.php">← Back to Projects</a>
    </div>

    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $tasks->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
            <td class="status"><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <a href="edit_task.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>">Edit</a>
                <a href="delete_task.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>" onclick="return confirm('Delete task?')">Delete</a>
                <a href="update_status.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>">Update Status</a>
                <a href="add_remark.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>">Add Remark</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
