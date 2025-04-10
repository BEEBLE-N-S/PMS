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

<h2>Project Report: <?= htmlspecialchars($project['name']) ?></h2>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($project['description'])) ?></p>
<hr>

<?php
$tasks = $conn->query("SELECT * FROM tasks WHERE project_id = $project_id");

while ($task = $tasks->fetch_assoc()) {
    echo "<h3>Task: " . htmlspecialchars($task['title']) . " (Status: " . $task['status'] . ")</h3>";
    echo "<p>Description: " . nl2br(htmlspecialchars($task['description'])) . "</p>";

    // Task Status History
    echo "<h4>Status History:</h4>";
    $history = $conn->query("SELECT * FROM task_status_history WHERE task_id = {$task['id']} ORDER BY changed_at DESC");
    if ($history->num_rows > 0) {
        echo "<ul>";
        while ($row = $history->fetch_assoc()) {
            echo "<li>" . $row['status'] . " on " . $row['changed_at'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No status changes yet.</p>";
    }

    // Remarks
    echo "<h4>Remarks:</h4>";
    $remarks = $conn->query("SELECT * FROM task_remarks WHERE task_id = {$task['id']} ORDER BY created_at DESC");
    if ($remarks->num_rows > 0) {
        echo "<ul>";
        while ($r = $remarks->fetch_assoc()) {
            echo "<li>" . $r['remark'] . " (added on " . $r['created_at'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No remarks added yet.</p>";
    }

    echo "<hr>";
}
?>

<a href="projects.php">← Back to Projects</a>
