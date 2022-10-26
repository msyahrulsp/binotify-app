<?php
  require 'controllers/MainController.php';

  $response = null;
  
  if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $user = new UserController($db);
    if ($user->isUnique($username, 'username')) {
      $response['status'] = 200;
      echo json_encode($response);
    } else {
      $response['status'] = 400;
      $response['message'] = "Username already exist.";
      echo json_encode($response);
    }
  }

  if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $user = new UserController($db);
  
    if ($user->isUnique($email, 'email')) {
      $response['status'] = 200;
      echo json_encode($response);
    } else {
      $response['status'] = 400;
      $response['message'] = "email already exist.";
      echo json_encode($response);
    }
  }

?>