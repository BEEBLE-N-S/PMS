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
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Task</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        <h2>Create Task</h2>
        <input type="text" name="title" required placeholder="Task Title">
        <textarea name="description" placeholder="Task Description"></textarea>
        <button type="submit">Save Task</button>
        <div class="back-link">
            <a href="tasks.php?project_id=<?= $project_id ?>">← Back to Tasks</a>
        </div>
    </form>
</div>

</body>
</html>
