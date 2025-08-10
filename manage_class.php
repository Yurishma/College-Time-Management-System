<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Timetable</title>
  <link rel="stylesheet" href="timetable.css">
</head>
<body>
  <div class="container">
    <a href="admindashboard.php" style="text-decoration: none; color: #007bff; font-weight: bold; display: inline-block; margin-bottom: 20px;">← Back to Dashboard</a>

    <h2>Generate Timetable</h2>

    <form id="timetableForm" action="generate_timetable.php" method="POST">
      <label for="department">Select Department:</label>
      <select id="department" name="department" required>
        <option value="">-- Select Department --</option>
        <option value="BCA">BCA</option>
        <option value="BBM">BBM</option>
        <option value="BBS">BBS</option>
      </select>

      <label for="level_type">Select Level Type:</label>
      <select id="level_type" name="level_type" required>
        <option value="">-- Select Type --</option>
        <option value="semester">Semester</option>
        <option value="year">Year</option>
      </select>

      <label for="level_number">Select Semester/Year Number:</label>
      <select id="level_number" name="level_number" required>
        <option value="">-- Select --</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
      </select>

      <button type="submit" id="generateBtn">Generate Timetable</button>
    </form>

    <div id="generatedTimetable" class="hidden">
      <h3>Generated Timetable</h3>
      <div id="timetableContainer">[ Timetable Preview Will Be Shown Here ]</div>
      <button id="saveTimetableBtn">Save Timetable</button>
    </div>
  </div>

  <script src="timetable.js"></script>
</body>
</html>
