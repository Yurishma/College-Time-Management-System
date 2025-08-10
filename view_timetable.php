<?php
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Timetable</title>
  <link rel="stylesheet" href="style.css"> <!-- use same style as manage_teacher -->
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    h2{
        text-align: center;
    }
    h3 {
      margin-top: 50px;
      margin-bottom: 10px;
      color: #333;
    }
    .edit-btn {
      padding: 4px 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      text-decoration: none;
    }
    .back-btn:hover 
    {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>BCA Timetable Overview</h2>
      <a href="admindashboard.php" class="back-button">&#8592; Back </a>


    <?php
    $query = "SELECT DISTINCT level_type, level_number FROM generated_timetable WHERE department='BCA' ORDER BY level_number";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $level_type = $row['level_type'];
        $level_number = $row['level_number'];

        echo "<h3>BCA - $level_type $level_number</h3>";

        $timetableQuery = "
            SELECT gt.*, t.name as teacher_name 
            FROM generated_timetable gt 
            LEFT JOIN teacher t ON gt.teacher_id = t.id
            WHERE gt.department='BCA' AND gt.level_type='$level_type' AND gt.level_number='$level_number'
            ORDER BY FIELD(gt.day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), gt.start_time
        ";

        $timetableResult = $conn->query($timetableQuery);

        if ($timetableResult->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                  </tr>";

            while ($row = $timetableResult->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['day']}</td>
                        <td>{$row['start_time']}</td>
                        <td>{$row['end_time']}</td>
                        <td>{$row['subject']}</td>
                        <td>{$row['teacher_name']}</td>
                        <td>
                          <a href='edit_timetable.php?id={$row['id']}' class='edit-btn'>Edit</a>
                        </td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No timetable found for BCA - $level_type $level_number.</p>";
        }
    }

    $conn->close();
    ?>
  </div>
</body>
</html>
