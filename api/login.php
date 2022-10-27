<?php
  require '../controllers/MainController.php';

  if (isset($_POST)) {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $response = null;

    if ($account == '' || $password == '') {
      $response['status'] = 400;
      $response['message'] = "Incorrect username or password";
      echo json_encode($response);
      return;
    }

    try {
      $user = new UserController($db);
      if (preg_match('#[a-zA-z0-9.-]+\@[a-zA-z0-9.-]+.[a-zA-Z]+#', $account)) {
        $curr_user = $user->getUser($account, 'email');

        if ($curr_user) {
          if (password_verify($password, $curr_user['password'])) {
            $_SESSION['user_id'] = $curr_user['user_id'];
            $_SESSION['user_name'] = $curr_user['name'];
            $_SESSION['isAdmin'] = $curr_user['isAdmin'];
  
            $response['status'] = 200;
            $response['message'] = "Login successfull";
            echo json_encode($response);
            return;
          } else {
            $response['status'] = 400;
            $response['message'] = "Incorrect username or password.";
            echo json_encode($response);
            return;
          }
        } else {
          $response['status'] = 400;
          $response['message'] = "Incorrect username or password.";
          echo json_encode($response);
          return;
        }
      } else {
        $curr_user = $user->getUser($account, 'username');
        if ($curr_user) {
          if (password_verify($password, $curr_user['password'])) {
            $_SESSION['user_id'] = $curr_user['user_id'];
            $_SESSION['user_name'] = $curr_user['name'];
            $_SESSION['isAdmin'] = $curr_user['isAdmin'];
  
            $response['status'] = 200;
            $response['message'] = "Login successfull";
            echo json_encode($response);
            return;
          } else {
            $response['status'] = 400;
            $response['message'] = "Incorrect username or password.";
            echo json_encode($response);
            return;
          }
        } else {
          $response['status'] = 400;
          $response['message'] = "Incorrect username or password.";
          echo json_encode($response);
          return;
        }
      }
    } catch (PDOException $e) {
      $response = array(
        "status" => 400,
        "message" => $e->getMessage()
      );
      echo json_encode($response);
      return;
    }

  }
?>