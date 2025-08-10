<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Set Subject Durations</title>
  <link rel="stylesheet" href="subject.css">
</head>
<body>
  <div class="container">
     <a href="admindashboard.php" class="back-button">&#8592; Back </a>
    <h2>Assign Duration to Subjects</h2>
    <form action="save_duration.php" method="POST">
      <label for="department">Select Department:</label>
      <select id="department" name="department" required>
        <option value="">--Select Department--</option>
        <option value="BCA">BCA</option>
        <option value="BBM">BBM</option>
        <option value="BBS">BBS</option>
      </select>

      <div id="semesterBlock" class="hidden">
        <p>Select Semester:</p>
        <div id="semesterCheckboxes"></div>
      </div>

      <div id="yearBlock" class="hidden">
        <p>Select Year:</p>
        <div id="yearCheckboxes"></div>
      </div>

      <div id="subjectBlock" class="hidden">
        <h3>Subjects:</h3>
        <div id="subjects"></div>
      </div>

      <div id="submitBlock" class="hidden">
        <button type="submit">Save Durations</button>
      </div>
    </form>
  </div>

  <script src="sub.js"></script>
</body>
</html>
