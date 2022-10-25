<?php
  require 'controllers/MainController.php';
  ob_start();
  session_start();

  if (!empty($_SESSION)) {
    redirect('/');
  }

  // if (isset($_POST['submit'])) {
  //   $name = $_POST['name'];
  //   $email = $_POST['email'];
  //   $username = $_POST['username'];
  //   $password = $_POST['password'];
  //   $confirm_password = $_POST['confirm_password'];

  //   try {
  //     $user = new UserController($db);
  //     if ($password === $confirm_password) {
  //       if (preg_match('#^[a-zA-Z0-9_.-]*$#', $username) && preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $email)) {
  //           $user->register($name, $email, $username, $password);
  //           $curr_user = $user->getUser($username);
  //           $_SESSION['user_id'] = $curr_user['user_id'];
  //           $_SESSION['user_name'] = $curr_user['name'];
  //           $_SESSION['isAdmin'] = $curr_user['isAdmin'];
  //           redirect('/');
  //       }
  //       if (!preg_match('#^[a-zA-Z0-9_.-]*$#', $username)) {
  //         echo "Username can only contain alphabets, numbers, and underscore!";
  //       }
  //       if (!preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $email)) {
  //         echo "Email not valid!";
  //       }
  //     } else {
  //       echo "Password not the same!";
  //     }
  //   } catch (PDOException $e) {
  //     echo $e->getMessage();
  //   }
  // }
  
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
  <form method="POST" action="" id="register-form">
    <label>What is your name?</label>
    <input type="text" placeholder="Enter your name." name="name" />
    <label>What is your email?</label>
    <input type="email" placeholder="Enter your email." name="email" id="email" oninput="setTimeout(() => checkUnique(this.value, 'email'), 1000)" />
    <label>Create a username</label>
    <input type="text" placeholder="Create a username." name="username" id="username" oninput="setTimeout(() => checkUnique(this.value, 'username'), 1000)" />
    <label>Create a password</label>
    <input type="password" placeholder="Enter your password." name="password" />
    <label>Confirm your password</label>
    <input type="password" placeholder="Enter your password again." name="confirm_password" />
    <p>By clicking on sign-up, you agree to Binotify's <a href="https://docs.google.com/document/d/1bdYy1bAk6tpwYCZfqUxErCIJuESzfYH-n8ijvaNP_Jg/edit" target="_blank">Terms and Condition of Use.</a></p>
    <p>To learn more about how Spotify collects, uses, shares and protects your personal data, please see <a href="https://docs.google.com/spreadsheets/d/1w4bKjk8J9dxbIr7w8ZDGBT5rH2wovTMDWS43Mm3i16Q/edit#gid=1057932904" target="_blank">Binotify's Privacy Policy.</a></p>
    <button type="submit" name="submit" id="sign-up">Sign Up</button>
  </form>
  <p>Have an account? <a href="/login.php">Log in.</a></p>
  </div>

  <script>
    document.getElementById('register-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(document.getElementById('register-form'));
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          if (response.status === 200) {
            window.location.href = '/'
          }
        }
      }
      xhttp.open('POST', 'api/register.php', true);
      xhttp.send(formData);
    })
    function checkUnique(str, field) {
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          if (this.responseText) {
            document.getElementById('sign-up').removeAttribute('disabled');
          } else {
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