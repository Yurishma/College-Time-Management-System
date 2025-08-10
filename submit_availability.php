<?php
session_start();
require 'db_connection.php';

$teacher_id = $_POST['teacher_id'];
$employment_type = $_POST['employment_type'];
$available_days = isset($_POST['available_days']) ? implode(",", $_POST['available_days']) : '';
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

if ($teacher_id && $employment_type && $available_days && $start_time && $end_time) {
    // Check if availability already exists
    $check = $conn->prepare("SELECT id FROM availability WHERE teacher_id = ?");
    $check->bind_param("i", $teacher_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update availability
        $update = $conn->prepare("UPDATE availability SET employment_type = ?, available_days = ?, start_time = ?, end_time = ? WHERE teacher_id = ?");
        $update->bind_param("ssssi", $employment_type, $available_days, $start_time, $end_time, $teacher_id);
        $update->execute();
        $message = "Availability updated successfully!";
    } else {
        // Insert new availability
        $insert = $conn->prepare("INSERT INTO availability (teacher_id, employment_type, available_days, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("issss", $teacher_id, $employment_type, $available_days, $start_time, $end_time);
        $insert->execute();
        $message = "Availability saved successfully!";
    }

    echo "<script>alert('$message'); window.location.href = 'Teacherdashboard.php';</script>";
    exit();
} else {
    echo "<script>alert('Please fill in all fields.'); window.location.href = 'my_availability.php';</script>";
    exit();
}
?>
