<?php
include 'db_connection.php'; // your DB connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $department = $_POST['department'];
    $durations = $_POST['duration'];
    $frequencies = $_POST['frequency']; // same structure as durations

    // Validate
    if (empty($department) || empty($durations) || empty($frequencies)) {
        die("Invalid input.");
    }

    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO subject_duration (department, level_type, level_number, subject, duration_mins, frequency) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($durations as $levelType => $levels) {
        foreach ($levels as $levelNumber => $subjects) {
            foreach ($subjects as $subject => $duration) {
                $freq = $frequencies[$levelType][$levelNumber][$subject] ?? null;

                if (!empty($duration) && !empty($freq)) {
                    $stmt->bind_param("ssisii", $department, $levelType, $levelNumber, $subject, $duration, $freq);
                    $stmt->execute();
                }
            }
        }
    }

    $stmt->close();
    $conn->close();

    echo "<script>alert('Subject durations saved successfully.'); window.location.href = 'admindashboard.php';</script>";
} else {
    echo "Unauthorized access.";
}
?>
