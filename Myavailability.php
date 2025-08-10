<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') {
    header("Location: login.php");
    exit();
}

// Get teacher_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT id FROM teacher WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$teacher_id = $teacher['id'];

// Check if availability already exists
$existing = $conn->prepare("SELECT * FROM availability WHERE teacher_id = ?");
$existing->bind_param("i", $teacher_id);
$existing->execute();
$availability_result = $existing->get_result();
$availability = $availability_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Availability</title>
    <link rel="stylesheet" href="Myavailability.css">
</head>
<body>
    <?php
if (isset($_SESSION['message'])) {
    echo "<div class='message-box'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
?>


<div class="availability-container">
         <a href="Teacherdashboard.php" class="back-button">&#8592; Back </a>

    <h2>Set Your Availability</h2>

    <form class="availability-form" action="submit_availability.php" method="POST">

        <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">

        <label for="employment_type">Employment Type:</label>
        <select name="employment_type" id="employment_type" required>
            <option value="">-- Select --</option>
            <option value="Full-time" <?php if (!empty($availability) && $availability['employment_type'] == 'Full-time') echo 'selected'; ?>>Full-time</option>
            <option value="Part-time" <?php if (!empty($availability) && $availability['employment_type'] == 'Part-time') echo 'selected'; ?>>Part-time</option>
        </select>

        <label>Available Days:</label>
        <div class="checkbox-group">
            <?php
            $days = ['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $selected_days = isset($availability['available_days']) ? explode(",", $availability['available_days']) : [];

            foreach ($days as $day) {
                $checked = in_array($day, $selected_days) ? "checked" : "";
                echo "<label><input type='checkbox' name='available_days[]' value='$day' $checked> $day</label>";
            }
            ?>
        </div>

        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" id="start_time" required value="<?php echo $availability['start_time'] ?? ''; ?>">

        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" id="end_time" required value="<?php echo $availability['end_time'] ?? ''; ?>">

        <input type="submit" value="<?php echo $availability ? 'Update Availability' : 'Save Availability'; ?>">
    </form>
</div>
</body>
</html>
