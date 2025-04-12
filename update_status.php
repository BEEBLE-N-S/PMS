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
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Task Status</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        select {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        <h2>Update Task Status</h2>
        <select name="status" required>
            <option value="">-- Select Status --</option>
            <option value="Pending" <?= $task['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Completed" <?= $task['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>
        <button type="submit">Update</button>
        <div class="back-link">
            <a href="tasks.php?project_id=<?= $project_id ?>">← Back to Tasks</a>
        </div>
    </form>
</div>

</body>
</html>
