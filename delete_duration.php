<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the entry
    $query = "DELETE FROM subject_duration WHERE id = $id";

    if ($conn->query($query)) {
        header("Location: manage_class.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
