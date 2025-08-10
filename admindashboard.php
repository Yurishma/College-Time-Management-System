<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" type="text/css" href="admin.css">
  
</head>
<body>

  <div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="ManageTeacherTable.php">Manage Teachers</a></li>
      <li><a href="subject_duration.php">Manage Classes</a></li>
      <li><a href="manage_class.php">Generate Timetable</a></li>
      <li><a href="view_timetable.php">View Timetable</a></li>
      <li><a href="#" onclick="if(confirm('Do you want to log out?')) { window.location.href='logout.php'; } return false;">Logout</a>
</li>
    </ul>
  </div>

  <div class="main">
    <h1>Welcome, Admin</h1>
    <div class="stats">
      <p><strong>Total Teachers:</strong> 9</p>
      <p><strong>Semesters:</strong> 8</p>
    </div>
  </div>

</body>
</html>
