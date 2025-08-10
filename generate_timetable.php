<?php
include 'db_connection.php';

// Configuration
$breaks = [
    'BCA' => ['start' => '09:00:00', 'end' => '09:30:00'],
    'BBM' => ['start' => '08:30:00', 'end' => '09:00:00'],
    'BBS' => ['start' => '08:30:00', 'end' => '09:00:00']
];

$max_classes_per_day = 4;
$max_end_time = strtotime('11:00:00');

// Get form filters
$department = $_POST['department'] ?? '';
$level_type = $_POST['level_type'] ?? '';
$level_number = $_POST['level_number'] ?? '';

if (!$department || !$level_type || !$level_number) {
    echo "<script>alert('Missing filter values.'); window.history.back();</script>";
    exit;
}

// Clear old timetable entries only for the filtered semester
$conn->query("DELETE FROM generated_timetable WHERE department='$department' AND level_type='$level_type' AND level_number=$level_number");

// Fetch subject durations and frequency
$subject_info = [];
$q = $conn->query("SELECT * FROM subject_duration");
while ($row = $q->fetch_assoc()) {
    $subject_info[$row['subject']] = [
        'duration' => (int) $row['duration_mins'],
        'frequency' => (int) $row['frequency']
    ];
}

// Fetch teacher availability
$availability_map = [];
$q = $conn->query("SELECT * FROM availability");
while ($row = $q->fetch_assoc()) {
    $availability_map[$row['teacher_id']] = $row;
}

// Fetch subjects grouped by semester and teacher
$assignments = $conn->query("SELECT * FROM teacher_assignment WHERE level_type='$level_type' AND level_number=$level_number");
$subjects_by_teacher = [];
while ($row = $assignments->fetch_assoc()) {
    $subjects_by_teacher[$row['teacher_id']][] = $row;
}

$teacher_schedule = [];
$semester_schedule = [];
$daily_periods = []; // [sem_key][day] => count

foreach ($subjects_by_teacher as $teacher_id => $subjects) {
    if (!isset($availability_map[$teacher_id])) continue;
    
    $avail = $availability_map[$teacher_id];
    $days = explode(',', $avail['available_days']);
    $start = strtotime($avail['start_time']);
    $end = strtotime($avail['end_time']);

    $dept = $conn->query("SELECT department FROM teacher WHERE id=$teacher_id")->fetch_assoc()['department'];

    foreach ($subjects as $entry) {
        $subject = $entry['subject'];
        $duration = $subject_info[$subject]['duration'] ?? 60;
        $frequency = $subject_info[$subject]['frequency'] ?? 2;
        $sem_key = $level_type . '-' . $level_number;

        $count = 0;
        foreach ($days as $day) {
            $curr = $start;
            while ($curr + ($duration * 60) <= min($end, $max_end_time)) {
                if ($count >= $frequency) break 2;

                // Respect break
                $slot_start = date('H:i:s', $curr);
                $slot_end = date('H:i:s', $curr + ($duration * 60));
                if (isset($breaks[$dept])) {
                    $brk = $breaks[$dept];
                    if (!($slot_end <= $brk['start'] || $slot_start >= $brk['end'])) {
                        $curr += 300;
                        continue;
                    }
                }

                // Check max periods for the semester per day
                if (($daily_periods[$sem_key][$day] ?? 0) >= $max_classes_per_day) {
                    break;
                }

                // Check teacher clash
                $overlap = false;
                if (isset($teacher_schedule[$teacher_id][$day])) {
                    foreach ($teacher_schedule[$teacher_id][$day] as $t) {
                        if (!($slot_end <= $t['start'] || $slot_start >= $t['end'])) {
                            $overlap = true;
                            break;
                        }
                    }
                }

                // Check semester clash
                if (isset($semester_schedule[$sem_key][$day])) {
                    foreach ($semester_schedule[$sem_key][$day] as $t) {
                        if (!($slot_end <= $t['start'] || $slot_start >= $t['end'])) {
                            $overlap = true;
                            break;
                        }
                    }
                }

                if ($overlap) {
                    $curr += 300;
                    continue;
                }

                // Save to DB
                $stmt = $conn->prepare("INSERT INTO generated_timetable (department, level_type, level_number, day, start_time, end_time, subject, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssissssi", $dept, $level_type, $level_number, $day, $slot_start, $slot_end, $subject, $teacher_id);
                $stmt->execute();

                // Trackers
                $teacher_schedule[$teacher_id][$day][] = ['start' => $slot_start, 'end' => $slot_end];
                $semester_schedule[$sem_key][$day][] = ['start' => $slot_start, 'end' => $slot_end];
                $daily_periods[$sem_key][$day] = ($daily_periods[$sem_key][$day] ?? 0) + 1;
                $count++;

                break;
            }
        }
    }
}

echo "<script>alert('Timetable generated successfully!'); window.location.href='manage_class.php';</script>";
?>
