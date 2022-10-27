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
  <link rel="stylesheet" type="text/css" href="css/register.css">
  <title>
    <?php
      echo "Binotify · Register";
    ?>
  </title>
</head>
<body>
  <header>
    <h1>Binotify</h1>
  </header>
  <main class="container">
    <form method="POST" action="" id="register-form" class="form__register">
      <div class="input-container">
        <label>What is your name?</label>
        <input type="text" placeholder="Enter your name." name="name" id="name" />
        <span class="error-message" id="error-name"></span>
      </div>
      <div class="input-container">
        <label>What is your email?</label>
        <input type="email" placeholder="Enter your email." name="email" id="email" oninput="debounceInput(this.value, 'email')" />
        <span class="error-message" id="error-email"></span>
      </div>
      <div class="input-container">
        <label>Create a username</label>
        <input type="text" placeholder="Create a username." name="username" id="username" onkeyup="debounceInput(this.value, 'username')" />
        <span class="error-message" id="error-username"></span>
      </div>
      <div class="input-container">
        <label>Create a password</label>
        <input type="password" placeholder="Create a password." name="password" id="password" />
        <span class="error-message" id="error-password"></span>
      </div>
      <div class="input-container">
        <label>Confirm your password</label>
        <input type="password" placeholder="Enter your password again." name="confirm_password" id="confirm_password" />
        <span class="error-message" id="error-confirm-password"></span>
      </div>
      <div class="information-container">
        <p>By clicking on sign-up, you agree to Binotify's <a href="https://docs.google.com/document/d/1bdYy1bAk6tpwYCZfqUxErCIJuESzfYH-n8ijvaNP_Jg/edit" target="_blank">Terms and Condition of Use.</a></p>
        <p>To learn more about how Spotify collects, uses, shares and protects your personal data, please see <a href="https://docs.google.com/spreadsheets/d/1w4bKjk8J9dxbIr7w8ZDGBT5rH2wovTMDWS43Mm3i16Q/edit#gid=1057932904" target="_blank">Binotify's Privacy Policy.</a></p>
     </div>
      <button type="submit" name="submit" id="sign-up" class="button__register">Sign Up</button>
    </form>
    <p class="login-link">Have an account? <a href="/login.php">Log in.</a></p>
  </main>

  <script>
    document.getElementById('register-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const name_input = document.getElementById('name');
      const email_input = document.getElementById('email');
      const username_input = document.getElementById('username');
      const password_input = document.getElementById('password');
      const confirm_password_input = document.getElementById('confirm_password');

      const error_name = document.getElementById('error-name');
      const error_email = document.getElementById('error-email');
      const error_username = document.getElementById('error-username');
      const error_password = document.getElementById('error-password');
      const error_confirm_password = document.getElementById('error-confirm-password');

      const formData = new FormData(document.getElementById('register-form'));
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          if (response.status === 200) {
            window.location.href = '/'
          } else {
            if (!response.exist) {
              if (response.empty_name.is_empty) {
                error_name.innerText = response.empty_name.message;
                name_input.classList.add('error');
              } else {
                error_name.innerText = '';
                name_input.classList.remove('error');
              }
              if (response.empty_email.is_empty) {
                error_email.innerText = response.empty_email.message;
                email_input.classList.add('error');
              } else {
                if (response.email_error) {
                error_email.innerText = response.email_error;
                email_input.classList.add('error');
                } else {
                  error_email.innerText = '';
                  email_input.classList.remove('error');
                }
              }
              if (response.empty_username.is_empty) {
                error_username.innerText = response.empty_username.message;
                username_input.classList.add('error');
              } else {
                if (response.username_error) {
                  error_username.innerText = response.username_error;
                  username_input.classList.add('error');
                } else {
                  error_username.innerText = '';
                  username_input.classList.remove('error');
                }
              }
              if (response.empty_password.is_empty) {
                error_password.innerText = response.empty_password.message;
                password_input.classList.add('error');
              } else {
                error_password.innerText = '';
                password_input.classList.remove('error');
              }
              if (response.empty_confirm_password.is_empty) {
                error_confirm_password.innerText = response.empty_confirm_password.message;
                confirm_password_input.classList.add('error');
              } else {
                error_confirm_password.innerText = '';
                confirm_password_input.classList.remove('error');
              }
            }
          }
        }
      }
      xhttp.open('POST', 'api/register.php', true);
      xhttp.send(formData);
    })
    function debounce(func, timeout) {
      let timer;
      return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args) }, timeout);
      }
    }
    function checkUnique(str, field) {
      const input = document.getElementById(field);
      const error_input = document.getElementById(`error-${field}`);

      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          if (response.status === 400) {
            error_input.innerText = response.message;
            input.classList.add('error');
          } else {
            error_input.innerText = '';
            input.classList.remove('error');
          }
        }
      };
      xhttp.open('GET', `api/check_unique.php?${field}=${str}`, true);
      xhttp.send();
    }
    const debounceInput = debounce((str, field) => checkUnique(str, field), 300);
  </script>
</body>
</html>