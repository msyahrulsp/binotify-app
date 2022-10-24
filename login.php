<?php
  require 'controllers/MainController.php';

  session_start();
  
  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new UserController($db);
    $curr_user = $user->getUser($username, $password);

    if ($curr_user) {
      echo "Login Berhasil!";
    } else {
      echo "Salah username atau password";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form method="POST" action="">
    <label>Username</label>
    <input type="text" placeholder="Enter username" name="username" />
    <label>Password</label>
    <input type="password" placeholder="Enter password" name="password" />
    <button type="submit" name="submit">Sign Up</button>
  </form>
</body>
</html>