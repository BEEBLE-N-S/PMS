<?php
require 'session.php';
require 'db.php';

$task_id = $_GET['id'];
$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

$task = $conn->query("SELECT t.* FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE t.id = $task_id AND p.user_id = $user_id")->fetch_assoc();

if (!$task) die("Task not found or access denied.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remark = $_POST['remark'];
    $stmt = $conn->prepare("INSERT INTO task_remarks (task_id, remark) VALUES (?, ?)");
    $stmt->bind_param("is", $task_id, $remark);
    $stmt->execute();
    header("Location: tasks.php?project_id=$project_id");
}
?>

<h2>Add Remark for Task: <?= htmlspecialchars($task['title']) ?></h2>
<form method="POST">
    <textarea name="remark" required></textarea><br><br>
    <button type="submit">Add Remark</button>
</form>
<a href="tasks.php?project_id=<?= $project_id ?>">Back</a>
