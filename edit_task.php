<?php
require 'session.php';
require 'db.php';

$id = $_GET['id'];
$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

$task = $conn->query("SELECT t.* FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE t.id = $id AND p.user_id = $user_id")->fetch_assoc();

if (!$task) die("Task not found or access denied.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $desc, $id);
    $stmt->execute();
    header("Location: tasks.php?project_id=$project_id");
}
?>

<h2>Edit Task</h2>
<form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br><br>
    <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br><br>
    <button type="submit">Update Task</button>
</form>
<a href="tasks.php?project_id=<?= $project_id ?>">Back</a>
