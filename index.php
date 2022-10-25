<?php
require('controllers/MainController.php');

// Test Connection
// $conn = $db->getConnection();

// $sql = 'select * from song';
// foreach ($conn->query($sql) as $row) {
//   echo $row['penyanyi'];
// }
// SUCCESS

session_start();
print_r($_SESSION);

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
    // ini urg komen soalnya blm ada data di db
    include('templates/song_list_section.php');
    echo '<h1>Success</h1>';
    ?>
  </div>
</body>

</html>