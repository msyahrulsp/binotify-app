<?php
  require 'controllers/MainController.php';
  
  if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $user = new UserController($db);
    if ($user->isUnique($username, 'username')) {
      echo TRUE;
    } else {
      echo FALSE;
    }
  }

  if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $user = new UserController($db);
  
    if ($user->isUnique($email, 'email')) {
      echo TRUE;
    } else {
      echo FALSE;
    }
  }

?>