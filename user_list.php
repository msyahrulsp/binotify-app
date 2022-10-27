<?php
  require "controllers/MainController.php";
  session_start();
  $user = new UserController($db);
  $user_list = $user->getAll();
  $total_user = $user->countUser();

  function echoUserList($user, $index) {
    $name = $user['name'];
    $email = $user['email'];
    $username = $user['username'];

    $html = <<<"EOT"
    <tr>
      <td>$index</td>
      <td>$email</td>
      <td>$name</td>
      <td>$username</td>
    </tr>
    EOT;
    
    echo $html;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="stylesheet" type="text/css" href="css/user_list.css">
  <title>
    <?php
      echo "Binotify · User List";
    ?>
  </title>
</head>
<body>
  <main class="container">
    <?php
      include('templates/navbar.php');
    ?>
    <section class="content">
      <header>
        <h2>User List</h2>
      </header>
      <table>
        <tr>
          <th>No</th>
          <th>Email</th>
          <th>Name</th>
          <th>Username</th>
        </tr>
        <?php
          for ($i = 1; $i <= $total_user; $i++) {
            echoUserList($user_list[$i-1], $i);
          }
        ?>
      </table>
    </section>
  </main>
</body>
</html>