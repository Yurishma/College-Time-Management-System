<?php

include 'db_connection.php'; // Your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'teacher')");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "<script>
                    alert('You are registered successfully!');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Username and password are required.";
    }
}

$conn->close();
?>
