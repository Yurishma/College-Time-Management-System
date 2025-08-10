<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "timetable";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$teacherData = null;
$assignments = [];
$levelType = '';

$teacherSql = "SELECT * FROM teacher WHERE user_id = ?";
$stmt = $conn->prepare($teacherSql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $teacherData = $result->fetch_assoc();

    // Fetch teacher assignments
    $assignSql = "SELECT * FROM teacher_assignment WHERE teacher_id = ?";
    $stmt2 = $conn->prepare($assignSql);
    $stmt2->bind_param("i", $teacherData['id']);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($row = $res2->fetch_assoc()) {
        $assignments[$row['level_number']][] = $row['subject'];
        $levelType = $row['level_type']; // set once
    }
    $stmt2->close();
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Signup</title>
  <link rel="stylesheet" type="text/css" href="Signup.css">
</head>
<body>
<h2>Teacher Signup Form</h2>
     <a href="Teacherdashboard.php" class="back-button">&#8592; Back </a>

<form action="signup_process.php" method="POST">
  <div class="form-group">
    <label>Name:</label>
    <input type="text" name="name" required value="<?php echo $teacherData['name'] ?? ''; ?>">
  </div>

  <div class="form-group">
    <label>Contact:</label>
    <input type="text" name="contact" required value="<?php echo $teacherData['contact'] ?? ''; ?>">
  </div>

  <div class="form-group">
    <label>Department:</label>
    <select name="department" id="department" required>
      <option value="">-- Select Department --</option>
      <option value="BCA" <?php if (($teacherData['department'] ?? '') === 'BCA') echo 'selected'; ?>>BCA</option>
      <option value="BBM" <?php if (($teacherData['department'] ?? '') === 'BBM') echo 'selected'; ?>>BBM</option>
      <option value="BBS" <?php if (($teacherData['department'] ?? '') === 'BBS') echo 'selected'; ?>>BBS</option>
    </select>
  </div>

  <!-- Semester Checkboxes -->
  <div class="form-group hidden" id="semesterBlock">
    <label>Select Semester(s):</label>
    <div id="semesterCheckboxes"></div>
  </div>

  <!-- Year Checkboxes -->
  <div class="form-group hidden" id="yearBlock">
    <label>Select Year(s):</label>
    <div id="yearCheckboxes"></div>
  </div>

  <!-- Subject Selection -->
  <div class="form-group hidden" id="subjectBlock">
    <label>Select Subject(s):</label>
    <div id="subjects"></div>
  </div>

  <button type="submit">Submit</button>
</form>

<script>
  const existingDepartment = "<?php echo $teacherData['department'] ?? ''; ?>";
  const levelType = "<?php echo $levelType ?? ''; ?>";
  const assignments = <?php echo json_encode($assignments); ?>;
</script>
<script src="Signup.js"></script>
</body>
</html>
