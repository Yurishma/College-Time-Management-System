<?php
session_start();
require 'db_connection.php';

$role = $_POST['role'];
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ? AND role = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admindashboard.php");
        } elseif ($role === 'teacher') {
            header("Location: Teacherdashboard.php"); // <-- fix case here
        }
        exit;
    } else {
        echo "Invalid password!";
    }
} else {
    echo "User not found!";
}
?>
