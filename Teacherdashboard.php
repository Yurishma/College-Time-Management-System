<?php
session_start();



if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Teacher') {
    header("Location: login.php");
    exit();
}



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "timetable";

$conn = new mysqli($servername, $username, $password, $dbname);
$user_id = $_SESSION['user_id'];

// Check if teacher has filled details
$sql = "SELECT * FROM teacher WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$teacherFilled = false;
if ($row = $result->fetch_assoc()) {
    $teacherFilled = true;
    $teacher_name = $row['name'];
    $teacher_id = $row['id'];
    // Fetch subjects and semesters
    $assignments = $conn->query("SELECT DISTINCT level_type, level_number, subject FROM teacher_assignment WHERE teacher_id = $teacher_id");
    $semesters = [];
    $subjects = [];
    while ($row2 = $assignments->fetch_assoc()) {
        $semesters[] = $row2['level_number'];
        $subjects[] = $row2['subject'];
    }
} else {
    $teacher_name = $_SESSION['username'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" type="text/css" href="teacher.css">
</head>
<body>
  <div class="sidebar">
    <h2>Teacher Panel</h2>
    <a href="#">Dashboard</a>
    <a href="Signup.php">My Details</a>
    <a href="Myavailability.php">My Availability</a>
    <a href="teacher_view_timetable.php">View Timetable</a>
    <a href="#" onclick="if(confirm('Do you want to log out?')) { window.location.href='logout.php'; } return false;">Logout</a>
  </div>

  <div class="main">
  <div class="card">
    <h2>Welcome, <?php echo htmlspecialchars($teacher_name); ?></h2>

    <?php if ($teacherFilled): ?>
      <p>Assigned Semesters: <?php echo count(array_unique($semesters)); ?></p>
      <p>Total Subjects: <?php echo count($subjects); ?></p>
    <?php else: ?>
      <p>Please fill in your details by clicking "My Details" in the sidebar.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
