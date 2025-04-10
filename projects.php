<?php
require 'session.php';
require 'db.php';

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM projects WHERE user_id = $user_id");
?>

<h2>Your Projects</h2>
<a href="create_project.php">+ Create Project</a> | 
<a href="logout.php">Logout</a>
<br><br>

<table border="1" cellpadding="10">
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
            <a href="edit_project.php?id=<?= $row['id'] ?>">Edit</a> | 
            <a href="delete_project.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this project?')">Delete</a> | 
            <a href="tasks.php?project_id=<?= $row['id'] ?>">View Tasks</a> | 
            <a href="project_report.php?project_id=<?= $row['id'] ?>">Report</a>
        </td>
    </tr>
    <?php } ?>
</table>
