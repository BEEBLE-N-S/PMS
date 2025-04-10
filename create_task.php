<?php
require 'session.php';
require 'db.php';

$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

$check = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$check->bind_param("ii", $project_id, $user_id);
$check->execute();
if (!$check->get_result()->fetch_assoc()) {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $project_id, $title, $desc);
    $stmt->execute();

    header("Location: tasks.php?project_id=$project_id");
}
?>

<h2>Create Task</h2>
<form method="POST">
    <input type="text" name="title" required placeholder="Task Title"><br><br>
    <textarea name="description" placeholder="Task Description"></textarea><br><br>
    <button type="submit">Save Task</button>
</form>
<a href="tasks.php?project_id=<?= $project_id ?>">Back</a>
