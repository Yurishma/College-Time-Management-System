<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) != 'teacher') {
    header("Location: login.php");
    exit();
}


$teacher_id = $_SESSION['user_id'];

// Fetch teacher name
$teacher_result = $conn->query("SELECT id, name FROM teacher WHERE user_id = $teacher_id");
$teacher = $teacher_result->fetch_assoc();
$teacher_name = $teacher ? $teacher['name'] : '';
$teacher_id = $teacher['id']; // Now we have correct teacher ID


// Fetch timetable for this teacher
$sql = "SELECT * FROM generated_timetable WHERE teacher_id = $teacher_id ORDER BY FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), start_time";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Timetable</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            width: 90%;
            margin: 30px auto;
        }
        h2 {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td, th {
            padding: 8px;
        }
        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <a class="back" href="Teacherdashboard.php">← Back to Dashboard</a>
    <h2>Welcome, <?= htmlspecialchars($teacher_name) ?>! Here's your Timetable</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Subject</th>
                    <th>Semester/Year</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['day'] ?></td>
                        <td><?= date('g:i A', strtotime($row['start_time'])) ?></td>
                        <td><?= date('g:i A', strtotime($row['end_time'])) ?></td>
                        <td><?= $row['subject'] ?></td>
                        <td><?= $row['level_type'] . ' ' . $row['level_number'] ?></td>
                        <td><?= $row['department'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No timetable found. Please contact the admin.</p>
    <?php endif; ?>
</div>

</body>
</html>
