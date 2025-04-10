<?php
require 'session.php';
require 'db.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
    die("Project not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    $stmt = $conn->prepare("UPDATE projects SET name = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $name, $desc, $id, $user_id);
    $stmt->execute();
    header("Location: projects.php");
    exit;
}
?>

<h2>Edit Project</h2>
<form method="POST">
    <input type="text" name="name" value="<?= htmlspecialchars($project['name']) ?>" required><br><br>
    <textarea name="description"><?= htmlspecialchars($project['description']) ?></textarea><br><br>
    <button type="submit">Update</button>
</form>
<a href="projects.php">Back</a>
