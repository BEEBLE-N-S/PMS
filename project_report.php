<?php
require 'session.php';
require 'db.php';

$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

// Check ownership
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

if (!$project) {
    die("Project not found or access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Report</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        h3 {
            margin-top: 30px;
            color: #007bff;
        }

        h4 {
            margin-bottom: 5px;
            color: #333;
        }

        p, li {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
        }

        ul {
            padding-left: 20px;
        }

        .section {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .no-data {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Project Report: <?= htmlspecialchars($project['name']) ?></h2>
    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($project['description'])) ?></p>

    <div class="section">
        <?php
        $tasks = $conn->query("SELECT * FROM tasks WHERE project_id = $project_id");

        if ($tasks->num_rows > 0) {
            while ($task = $tasks->fetch_assoc()) {
                ?>
                <h3>Task: <?= htmlspecialchars($task['title']) ?> <small>(Status: <?= $task['status'] ?>)</small></h3>
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($task['description'])) ?></p>

                <h4>Status History:</h4>
                <?php
                $history = $conn->query("SELECT * FROM task_status_history WHERE task_id = {$task['id']} ORDER BY changed_at DESC");
                if ($history->num_rows > 0) {
                    echo "<ul>";
                    while ($row = $history->fetch_assoc()) {
                        echo "<li>{$row['status']} on {$row['changed_at']}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p class='no-data'>No status changes yet.</p>";
                }

                echo "<h4>Remarks:</h4>";
                $remarks = $conn->query("SELECT * FROM task_remarks WHERE task_id = {$task['id']} ORDER BY created_at DESC");
                if ($remarks->num_rows > 0) {
                    echo "<ul>";
                    while ($r = $remarks->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($r['remark']) . " <small>(added on {$r['created_at']})</small></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p class='no-data'>No remarks added yet.</p>";
                }

                echo "<div class='section'></div>";
            }
        } else {
            echo "<p class='no-data'>No tasks found for this project.</p>";
        }
        ?>
    </div>

    <a class="back-link" href="projects.php">← Back to Projects</a>
</div>
</body>
</html>
