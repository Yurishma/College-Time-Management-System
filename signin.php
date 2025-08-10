<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Signin</title>
    <link rel="stylesheet" type="text/css" href="signin.css">

</head>
<body>

  <div class="signup-container">
    <h2>Teacher Signup</h2>
    <form action="signin_teacher.php" method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>

      <button type="submit">Sign In</button>
    </form>
  </div>

</body>
</html>
