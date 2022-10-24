<?php
  require 'controllers/MainController.php';

  session_start();

  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    try {
      $user = new UserController($db);
      if ($password === $confirm_password) {
        echo "Nice";
        // $user->register($name, $email, $username, $password);
      } else {
        echo "Password not the same!";
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
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
    <label>What is your name?</label>
    <input type="text" placeholder="Enter your name." name="name" />
    <label>What is your email?</label>
    <input type="email" placeholder="Enter your email." name="email" id="email" onchange="checkUnique(this.value, 'email')" />
    <label>Create a username</label>
    <input type="text" placeholder="Create a username." name="username" id="username" onchange="checkUnique(this.value, 'username')" />
    <label>Create a password</label>
    <input type="password" placeholder="Enter your password." name="password" />
    <label>Confirm your password</label>
    <input type="password" placeholder="Enter your password again." name="confirm_password" />
    <button type="submit" name="submit" id="sign-up">Sign Up</button>
  </form>
  <div id="status">
  </div>

  <script>
    document.getElementById('sign-up').addEventListener('click', function(event) {
      event.preventDefault();
    })

    function checkUnique(str, field) {
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          console.log(this.responseText)
          if (this.responseText) {
            console.log("true")
            document.getElementById('sign-up').removeAttribute('disabled');
          } else {
            console.log("false")
            document.getElementById('sign-up').setAttribute('disabled', true);
          }
          // document.getElementById('status').innerText = this.responseText;
        }
      };
      xhttp.open('GET', `check_unique.php?${field}=${str}`, true);
      xhttp.send();
    }
  </script>
</body>
</html>