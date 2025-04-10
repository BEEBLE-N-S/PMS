<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: projects.php");
    } else {
        echo "Invalid credentials.";
    }

    $stmt->close();
}
?>

<form method="POST">
    <h2>Login</h2>
    <input type="email" name="email" required placeholder="Email"><br><br>
    <input type="password" name="password" required placeholder="Password"><br><br>
    <button type="submit">Login</button>
</form>
