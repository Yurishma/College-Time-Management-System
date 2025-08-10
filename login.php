<?php
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>College Time Management - Login</title>
  <link rel="stylesheet" type="text/css" href="log.css">

    
</head>
<body>

  <div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="authenticate.php">
      <select name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        
      </select>

      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Login</button>
    </form>

    <div class="footer">
      Forgot your password? <a href="forget_password.php">Click here</a>
    </div>

    <div class="footer1">
      <p>Don't have an account? <a href="signin.php">Sign up here</a></p>

  </div>

</body>
</html>
