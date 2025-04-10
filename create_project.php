<?php
require 'session.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO projects (user_id, name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $name, $desc);

    if ($stmt->execute()) {
        header("Location: projects.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2>Create Project</h2>
<form method="POST">
    <input type="text" name="name" required placeholder="Project Name"><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <button type="submit">Save</button>
</form>
<a href="projects.php">Back</a>
