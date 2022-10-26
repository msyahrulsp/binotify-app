<?php
require('controllers/MainController.php');

// Test Connection
// $conn = $db->getConnection();

// $sql = 'select * from song';
// foreach ($conn->query($sql) as $row) {
//   echo $row['penyanyi'];
// }
// SUCCESS
ob_start();
session_start();

$logout = null;
if (!empty($_SESSION)) {
  $logout = '<button type="submit" name="logout">Log out</button>';
}

if (isset($_POST['logout'])) {
  session_destroy();
  redirect('/');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" type="text/css" href="css/song_list_section.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div>
    <?php
    include('templates/song_list_section.php');
    echo '<h1>Success</h1>';

    ?>
    <form method="POST">
      <?php echo $logout ?>
    </form>
  </div>
</body>

</html>