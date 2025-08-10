<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM subject_duration WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $subject = $row['subject'];
        $duration = $row['duration_mins'];
    } else {
        echo "Subject not found.";
        exit;
    }
} else {
    echo "No ID specified.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newDuration =intval($_POST['duration_mins']);
    $updateQuery = "UPDATE subject_duration SET duration_mins = $newDuration WHERE id = $id";

    if ($conn->query($updateQuery)) {
        header("Location: manage_class.php");
        exit;
    } else {
        echo "Error updating duration: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Duration</title>
    <link rel="stylesheet" href="manage.css">
</head>
<body>
<div class="container">
    <h2>Edit Duration for: <?php echo htmlspecialchars($subject); ?></h2>
    <form method="POST">
        <label>Duration (minutes):</label><br>
        <input type="number" name="duration_mins" value="<?php echo $duration; ?>" required><br><br>
        <button type="submit">Update</button>
    </form>

</div>
</body>
</html>
