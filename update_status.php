<?php
require 'session.php';
require 'db.php';

$task_id = $_GET['id'];
$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

$task = $conn->query("SELECT t.* FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE t.id = $task_id AND p.user_id = $user_id")->fetch_assoc();

if (!$task) die("Task not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    // Update task
    $stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $task_id);
    $stmt->execute();

    // Insert into history
    $stmt2 = $conn->prepare("INSERT INTO task_status_history (task_id, status) VALUES (?, ?)");
    $stmt2->bind_param("is", $task_id, $status);
    $stmt2->execute();

    header("Location: tasks.php?project_id=$project_id");
}
?>

<h2>Update Task Status</h2>
<form method="POST">
    <select name="status" required>
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
    </select><br><br>
    <button type="submit">Update</button>
</form>
<a href="tasks.php?project_id=<?= $project_id ?>">Back</a>
