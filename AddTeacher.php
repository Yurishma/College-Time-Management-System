<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Add Teacher</title>
  <link rel="stylesheet" type="text/css" href="AddTeach.css">
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="#">Dashboard</a>
    <a href="#">Manage Teachers</a>
    <a href="#">Manage Students</a>
    <a href="#">Manage Timetable</a>
    <a href="#">Logout</a>
  </div>

  <div class="main-content">
    <h2>Add New Teacher</h2>
    <form action="insert_teacher.php" method="POST">
      <div>
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required>
      </div>
      <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div>
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" required>
      </div>
      <div>
        <label for="type">Type</label>
        <select name="type" id="type" required>
          <option value="">Select Type</option>
          <option value="Full-Time">Full-Time</option>
          <option value="Part-Time">Part-Time</option>
        </select>
      </div>
      <div>
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" required>
      </div>
      <button type="submit">Add Teacher</button>
    </form>
  </div>
</body>
</html>
