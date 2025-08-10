<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname="timetable"; // Change this

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$step = 1;

// Step 1: Handle username submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && !isset($_POST['new_password'])) {
    $enteredUsername = $_POST['username'];
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $step = 2; // Username found, go to next step
    } else {
        $message = "Username not found!";
    }
}

// Step 2: Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    $usernameToUpdate = $_POST['username'];

    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $usernameToUpdate);

    if ($stmt->execute()) {
        $message = "Password updated successfully!";
        $step = 1;
    } else {
        $message = "Something went wrong!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            border: none;
        }

        .message {
            margin-top: 10px;
            color: red;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Reset Password</h2>

        <?php if ($step === 1): ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Enter your username" required>
                <button type="submit">Next</button>
            </form>
        <?php elseif ($step === 2): ?>
            <form method="POST">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($enteredUsername); ?>">
                <input type="password" name="new_password" placeholder="Enter new password" required>
                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>

        <div class="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
