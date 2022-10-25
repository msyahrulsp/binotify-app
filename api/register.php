<?php
  require '../controllers/MainController.php';
  ob_start();
  session_start();

  if (isset($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $response = null;

    if ($name == '' || $email == '' || $username == '' || $password == '' || $confirm_password == '') {
      $response['status'] = 400;
      if ($name == '') {
        $empty_name['message'] = "You need to enter your name.";
        $empty_name['is_empty'] = TRUE;
        $response['empty_name'] = $empty_name;
      } else {
        $response['empty_name'] = FALSE;
      }
      if ($email == '') {
        $empty_email['message'] = "You need to enter your email.";
        $empty_email['is_empty'] = TRUE;
        $response['empty_email'] = $empty_email;
      } else {
        $response['empty_email'] = FALSE;
      }
      if ($username == '') {
        $empty_username['message'] = "You need to entar a username.";
        $empty_username['is_empty'] = TRUE;
        $response['empty_username'] = $empty_username;
      } else {
        $response['empty_username'] = FALSE;
      }
      if ($password == '') {
        $empty_password['message'] = "You need to entar a password.";
        $empty_password['is_empty'] = TRUE;
        $response['empty_password'] = $empty_password;
      } else {
        $response['empty_password'] = FALSE;
      }
      if ($confirm_password == '') {
        $empty_confirm_password['message'] = "You need to enter your password again.";
        $empty_confirm_password['is_empty'] = TRUE;
        $response['empty_confirm_password'] = $empty_confirm_password;
      } else {
        $response['empty_confirm_password'] = FALSE;
      }
      echo json_encode($response);
      return;
    }


    try {
      $user = new UserController($db);
      if ($password === $confirm_password) {
        if (preg_match('#^[a-zA-Z0-9_.-]*$#', $username) && preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $email)) {
            $registerStatus = $user->register($name, $email, $username, $password);
            $curr_user = $user->getUser($username);
            $_SESSION['user_id'] = $curr_user['user_id'];
            $_SESSION['user_name'] = $curr_user['name'];
            $_SESSION['isAdmin'] = $curr_user['isAdmin'];
            if ($registerStatus) {
              $response['status'] = 200;
              $response['message'] = "Registration successfull.";
            } else {
              $response['status'] = 400;
              $response['message'] = "Registration unsuccessfull.";
            }
            echo json_encode($response);
            return;
        }
        $response["status"] = 400;
        if (!preg_match('#^[a-zA-Z0-9_.-]*$#', $username)) {
          $response["username_error"] = "Username can only contain alphabets, numbers, and underscore.";
        }
        if (!preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $email)) {
          $response["email_error"] = "Email not valid.";
        }
      } else {
        $response = array(
          "status" => 400,
          "password_error" => "The passwords don't match."
        );
      }
    } catch (PDOException $e) {
      $response = array(
        "status" => 400,
        "message" => $e->getMessage()
      );
      echo json_encode($response);
      return;
    }

    echo json_encode($response);
  }
?>