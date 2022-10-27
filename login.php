<?php
  require 'controllers/MainController.php';
  ob_start();

  if (!empty($_SESSION)) {
    redirect('/');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <title>
    <?php
      echo "Binotify · Login";
    ?>
  </title>
</head>
<body>
  <header>
    <h1>Binotify</h1>
  </header>
  <main class="container">
    <form method="POST" action="" class="form__login" id="login-form">
      <div id="error-container">
      </div>
      <div class="input-container">
        <label>Email address or username</label>
        <input type="text" placeholder="Email address or username" name="account" />
      </div>
      <div class="input-container">
        <label>Password</label>
        <input type="password" placeholder="Password" name="password" />
      </div>
      <div class=button-container__login>
        <button type="submit" name="submit" class="button__login">LOG IN</button>
      </div>
    </form>
    <div class="register-link">
      <h3>Don't have an account?</h3>
      <a href="/register.php">
        <button>SIGN UP FOR BINOTIFY</button>
      </a>
    </div>
  </main>
  <script>
    document.getElementById("login-form").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(document.getElementById("login-form"));
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          if (response.status === 200) {
            window.location.href = '/';
          } else {
            document.getElementById('error-container').innerHTML = `
              <div class="error-message">
                <p>${response.message}</p>
              </div>
            `
          }
        }
      }
      xhttp.open("POST", "api/login.php", true);
      xhttp.send(formData);
    })
  </script> 
</body>
</html>