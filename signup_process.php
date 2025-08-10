<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "timetable"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get teacher form input
$name = $_POST['name'];
$contact = $_POST['contact'];
$department = $_POST['department'];

$user_id = $_SESSION['user_id'];

// Check if teacher already exists
$checkQuery = "SELECT id FROM teacher WHERE user_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();
$stmt->close();

if ($existing) {
    // UPDATE existing teacher
    $teacher_id = $existing['id'];
    $updateQuery = "UPDATE teacher SET name = ?, contact = ?, department = ? WHERE user_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $name, $contact, $department, $user_id);
    $stmt->execute();
    $stmt->close();

    // Delete old assignments
    $conn->query("DELETE FROM teacher_assignment WHERE teacher_id = $teacher_id");
} else {
    // INSERT new teacher
    $insertQuery = "INSERT INTO teacher (user_id, name, contact, department) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isss", $user_id, $name, $contact, $department);
    $stmt->execute();
    $teacher_id = $stmt->insert_id;
    $stmt->close();
}

// Handle assignments
$level_type = '';
$levels = [];

if (!empty($_POST['semesters'])) {
    $level_type = 'semester';
    $levels = $_POST['semesters'];
} elseif (!empty($_POST['years'])) {
    $level_type = 'year';
    $levels = $_POST['years'];
}

if (!empty($_POST['subjects']) && !empty($levels)) {
    $stmt = $conn->prepare("INSERT INTO teacher_assignment (teacher_id, level_type, level_number, subject) VALUES (?, ?, ?, ?)");

    foreach ($_POST['subjects'] as $level_number => $subjectList) {
        foreach ($subjectList as $subject) {
            $stmt->bind_param("isis", $teacher_id, $level_type, $level_number, $subject);
            $stmt->execute();
        }
    }
    $stmt->close();
}

// Store in session
$_SESSION['teacher_id'] = $teacher_id;
$_SESSION['teacher_name'] = $name;

// Success and redirect
echo "<script>
    alert('Signup details saved successfully!');
    window.location.href = 'Teacherdashboard.php';
</script>";
exit();
?>
