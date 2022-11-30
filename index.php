<?php
require('controllers/MainController.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/song_list_section.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="icon" href="assets/images/component/spotify.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
    echo "Binotify · Home";
    ?>
  </title>
</head>

<body>
  <div class="container">
    <?php
    include('templates/navbar.php');
    include('templates/song_list_section.php');
    ?>
  </div>
</body>

</html>