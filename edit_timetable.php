<?php
include 'db_connection.php';

if (!isset($_GET['id'])) {
    echo "Invalid timetable ID.";
    exit;
}

$id = $_GET['id'];
$message = "";

// Fetch current data
$query = "SELECT gt.*, t.name as teacher_name FROM generated_timetable gt 
          LEFT JOIN teacher t ON gt.teacher_id = t.id 
          WHERE gt.id = $id";
$result = $conn->query($query);

if ($result->num_rows != 1) {
    echo "Timetable entry not found.";
    exit;
}

$row = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $subject = $_POST['subject'];

    $stmt = $conn->prepare("UPDATE generated_timetable SET day = ?, start_time = ?, end_time = ?, subject = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $day, $start_time, $end_time, $subject, $id);

    if ($stmt->execute()) {
        $message = "Timetable updated successfully.";
        // Refresh the row with updated data
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
    } else {
        $message = "Error updating timetable: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Timetable</title>
    <link rel="stylesheet" href="style.css"> <!-- Use same CSS -->
    <style>
        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 7px;
            margin-top: 5px;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Timetable</h2>

    <?php if ($message): ?>
        <p class="success"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="day">Day:</label>
        <select name="day" required>
            <?php
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($days as $d) {
                $selected = $d === $row['day'] ? 'selected' : '';
                echo "<option value='$d' $selected>$d</option>";
            }
            ?>
        </select>

        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" value="<?= $row['start_time'] ?>" required>

        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" value="<?= $row['end_time'] ?>" required>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" value="<?= $row['subject'] ?>" required>

        <button type="submit" class="submit-btn">Update</button>
    </form>

    <a href="view_timetable.php" class="back-link">← Back to Timetable</a>
</div>

</body>
</html>
