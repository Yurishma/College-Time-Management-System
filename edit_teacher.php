<?php
include 'db_connection.php';

if (!isset($_GET['id'])) {
    echo "Invalid Request!";
    exit;
}

$teacher_id = intval($_GET['id']);

// Fetch teacher details
$teacher = $conn->query("SELECT * FROM teacher WHERE id = $teacher_id")->fetch_assoc();
$assignment_q = $conn->query("SELECT * FROM teacher_assignment WHERE teacher_id = $teacher_id");
$availability = $conn->query("SELECT * FROM availability WHERE teacher_id = $teacher_id")->fetch_assoc();

// Prepare existing values
$assigned_subjects = [];
$assigned_semesters = [];
while ($row = $assignment_q->fetch_assoc()) {
    $assigned_subjects[] = $row['subject'];
    $assigned_semesters[] = $row['level_type'] . '-' . $row['level_number'];
}

// On submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $department = $_POST['department'];
    $employment_type = $_POST['employment_type'];
    $available_days = implode(',', $_POST['available_days']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $subjects = $_POST['subjects'];
    $semesters = $_POST['semesters'];

    // Update teacher table
    $stmt = $conn->prepare("UPDATE teacher SET name = ?, contact = ?, department = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $contact, $department, $teacher_id);
    $stmt->execute();

    // Update availability
    $stmt = $conn->prepare("UPDATE availability SET employment_type = ?, available_days = ?, start_time = ?, end_time = ? WHERE teacher_id = ?");
    $stmt->bind_param("ssssi", $employment_type, $available_days, $start_time, $end_time, $teacher_id);
    $stmt->execute();

    // Update assignments
    $conn->query("DELETE FROM teacher_assignment WHERE teacher_id = $teacher_id");
    foreach ($subjects as $i => $subj) {
        list($type, $num) = explode('-', $semesters[$i]);
        $stmt = $conn->prepare("INSERT INTO teacher_assignment (teacher_id, subject, level_type, level_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $teacher_id, $subj, $type, $num);
        $stmt->execute();
    }

    header("Location: ManageTeacherTable.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
      <link rel="stylesheet" type="text/css" href="edit_teacher.css">

</head>
<body>
    <h2>Edit Teacher</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($teacher['name']) ?>" required>

        <label>Contact:</label>
        <input type="text" name="contact" value="<?= htmlspecialchars($teacher['contact']) ?>" required>

        <label>Department:</label>
        <select name="department" required>
            <?php foreach (['BCA', 'BBM', 'BBS'] as $dept): ?>
                <option value="<?= $dept ?>" <?= $teacher['department'] == $dept ? 'selected' : '' ?>><?= $dept ?></option>
            <?php endforeach; ?>
        </select>

        <label>Employment Type:</label>
        <select name="employment_type" required>
            <option value="full-time" <?= $availability['employment_type'] == 'full-time' ? 'selected' : '' ?>>Full-Time</option>
            <option value="part-time" <?= $availability['employment_type'] == 'part-time' ? 'selected' : '' ?>>Part-Time</option>
        </select>

        <label>Available Days:</label>
        <?php
        $available_days = explode(',', $availability['available_days']);
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        ?>
    <div class="day-checkbox-group">
         <?php foreach ($days as $day): ?>
        <label class="day-checkbox">
            <input type="checkbox" name="available_days[]" value="<?= $day ?>"
                <?= in_array($day, $available_days) ? 'checked' : '' ?>>
            <?= $day ?>
        </label>
        <?php endforeach; ?>
    </div>


        <label>Start Time:</label>
        <input type="time" name="start_time" value="<?= $availability['start_time'] ?>" required>

        <label>End Time:</label>
        <input type="time" name="end_time" value="<?= $availability['end_time'] ?>" required>

        <div id="assignmentSection">
    <?php foreach ($assigned_subjects as $i => $subject): ?>
        <div class="assignment-pair">
            <textarea name="subjects[]" rows="2" placeholder="Subject"><?= htmlspecialchars($subject) ?></textarea>
            <select name="semesters[]">
                <?php for ($j = 1; $j <= 8; $j++): ?>
                    <?php $semValue = "semester-$j"; ?>
                    <option value="<?= $semValue ?>" <?= in_array($semValue, $assigned_semesters) && $assigned_semesters[$i] === $semValue ? 'selected' : '' ?>>Semester <?= $j ?></option>
                <?php endfor; ?>
            </select>
        </div>
    <?php endforeach; ?>

    <!-- Empty row if none exist -->
    <?php if (empty($assigned_subjects)): ?>
        <div class="assignment-pair">
            <textarea name="subjects[]" rows="2" placeholder="Subject"></textarea>
            <select name="semesters[]">
                <?php for ($j = 1; $j <= 8; $j++): ?>
                    <option value="semester-<?= $j ?>">Semester <?= $j ?></option>
                <?php endfor; ?>
            </select>
        </div>
    <?php endif; ?>
</div>

<button type="button" onclick="addAssignment()">Add More</button>

       

        <br><br>
        <button type="submit">Update Teacher</button>
    </form>
    <script src="edit_teacher.js"></script>
</body>
</html>
