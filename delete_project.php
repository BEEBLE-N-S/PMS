<?php
require 'session.php';
require 'db.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

header("Location: projects.php");
exit;
