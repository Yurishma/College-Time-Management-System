<!DOCTYPE html>
<html>
<head>
    <title>Manage Teachers</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        .actions a { margin-right: 10px; text-decoration: none; }
        h2 { margin-top: 20px; text-align: center; }

        .back-button {
    display: inline-block;
    margin: 20px;
    padding: 8px 12px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
}

.back-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
  <a href="admindashboard.php" class="back-button">&#8592; Back </a>


<h2>Manage Teachers</h2>

<?php include 'fetch_teachers.php'; ?>

</body>
</html>
