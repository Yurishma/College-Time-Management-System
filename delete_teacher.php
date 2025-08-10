<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete from related tables first
    $conn->query("DELETE FROM teacher_assignment WHERE teacher_id = $id");
    $conn->query("DELETE FROM availability WHERE teacher_id = $id");
    $conn->query("DELETE FROM teacher WHERE id = $id");

    header("Location: ManageTeacherTable.php");
    exit;
} else {
    echo "Invalid request.";
}
