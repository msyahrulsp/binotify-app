<?php
  require '../controllers/MainController.php';

  if (isset($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $response = null;

    if ($name == '' || $email == '' || $username == '' || $password == '' || $confirm_password == '') {
      $response['status'] = 400;
      $response['empty_message'] = "Please fill in all input field!";
      echo json_encode($response);
      return;
    }

    try {
      $user = new UserController($db);
      if ($password === $confirm_password) {
        if (preg_match('#^[a-zA-Z0-9_.-]*$#', $username) && preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $email)) {
            $user->register($name, $email, $username, $password);
            $curr_user = $user->getUser($username);
            $_SESSION['user_id'] = $curr_user['user_id'];
            $_SESSION['user_name'] = $curr_user['name'];
            $_SESSION['isAdmin'] = $curr_user['isAdmin'];
            redirect('/');
        }
        $response["status"] = 400;
        if (!preg_match('#^[a-zA-Z0-9_.-]*$#', $username)) {
          $response["username_error"] = "Username can only contain alphabets, numbers, and underscore!";
        }
        if (!preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $email)) {
          $response["email_error"] = "Email not valid!";
        }
      } else {
        $response = array(
          "status" => 400,
          "password_error" => "Password is different!"
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