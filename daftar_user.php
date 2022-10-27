<?php
  require "controllers/MainController.php";
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
  <title>
    <?php
      echo "Binotify · Daftar User";
    ?>
  </title>
</head>
<body>
  <main>
    <section>
      <header>
        <h2>Daftar User</h2>
      </header>
    </section>
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
  </main>
</body>
</html>