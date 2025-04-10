<?php
require 'session.php';
require 'db.php';

$id = $_GET['id'];
$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

$conn->query("DELETE t FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE t.id = $id AND p.user_id = $user_id");

header("Location: tasks.php?project_id=$project_id");
