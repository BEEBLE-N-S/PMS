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

<h2>Tasks for Project: <?= htmlspecialchars($project['name']) ?></h2>
<a href="create_task.php?project_id=<?= $project_id ?>">+ Add Task</a> | 
<a href="projects.php">← Back to Projects</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>Title</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $tasks->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= $row['status'] ?></td>
        <td>
            <a href="edit_task.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>">Edit</a> | 
            <a href="delete_task.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>" onclick="return confirm('Delete task?')">Delete</a> | 
            <a href="update_status.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>">Update Status</a> | 
            <a href="add_remark.php?id=<?= $row['id'] ?>&project_id=<?= $project_id ?>">Add Remark</a>
        </td>
    </tr>
    <?php } ?>
</table>
