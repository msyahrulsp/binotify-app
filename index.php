<?php
  require('controllers/MainController.php');

  // Test Connection
  $conn = $db->getConnection();

  $sql = 'select * from user';
  foreach ($conn->query($sql) as $row) {
    echo $row['email'];
  }
  // SUCCESS
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div>
    <?php
    // ini urg komen soalnya blm ada data di db
    // include('templates/song_list_section.php');
    ?>
  </div>
</body>

</html>