<?php
  require '../controllers/MainController.php';

  if (isset($_GET)) {
    $creator_id = $_GET['creator_id'];
    echo $creator_id;
  }
?>