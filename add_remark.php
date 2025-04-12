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
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Remark</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fb;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background: white;
            padding: 30px 25px;
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
            text-align: center;
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 15px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .back-link {
            margin-top: 15px;
            text-align: center;
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
        <h2>Add Remark for Task: <?= htmlspecialchars($task['title']) ?></h2>
        <textarea name="remark" required placeholder="Write your remark..."></textarea>
        <button type="submit">Add Remark</button>
        <div class="back-link">
            <a href="tasks.php?project_id=<?= $project_id ?>">← Back to Tasks</a>
        </div>
    </form>
</div>

</body>
</html>
