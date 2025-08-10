<?php
include 'db_connection.php';

// Fetch all teachers
$teachers = $conn->query("SELECT * FROM teacher");

function getAssignments($conn, $teacher_id) {
    $q = $conn->query("SELECT * FROM teacher_assignment WHERE teacher_id = $teacher_id");
    $subjects = [];
    $semesters = [];
    while ($row = $q->fetch_assoc()) {
        $subjects[] = $row['subject'];
        $semesters[] = $row['level_type'] . ' ' . $row['level_number'];
    }
    return ['subjects' => $subjects, 'semesters' => $semesters];
}

function getAvailability($conn, $teacher_id) {
    $q = $conn->query("SELECT * FROM availability WHERE teacher_id = $teacher_id");
    return $q->fetch_assoc();
}
?>

<table>
    <tr>
        <th>id</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Department</th>
        <th>Subjects</th>
        <th>Semesters/Years</th>
        <th>Employment</th>
        <th>Available Days</th>
        <th>Available Time</th>
        <th>Actions</th>
    </tr>

    <?php while ($teacher = $teachers->fetch_assoc()): 
        $info = getAssignments($conn, $teacher['id']);
        $avail = getAvailability($conn, $teacher['id']);
    ?>
    <tr>
        <td><?= $teacher['id'] ?></td>
        <td><?= htmlspecialchars($teacher['name']) ?></td>
        <td><?= htmlspecialchars($teacher['contact']) ?></td>
        <td><?= htmlspecialchars($teacher['department']) ?></td>
        <td><?= implode(', ', $info['subjects']) ?></td>
        <td><?= implode(', ', $info['semesters']) ?></td>
        <td><?= $avail ? ucfirst($avail['employment_type']) : 'N/A' ?></td>
        <td><?= $avail ? $avail['available_days'] : 'N/A' ?></td>
        <td>
            <?php
                if ($avail) {
                    echo date('h:i A', strtotime($avail['start_time'])) . ' - ' . date('h:i A', strtotime($avail['end_time']));
                } else {
                    echo 'N/A';
                }
            ?>
        </td>
        <td class="actions">
            <a href="edit_teacher.php?id=<?= $teacher['id'] ?>">✏️ Edit</a>
            <a href="delete_teacher.php?id=<?= $teacher['id'] ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
