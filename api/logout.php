<?php
  require '../controllers/MainController.php';
  ob_start();
  if (!empty($_SESSION)) {
    session_destroy();
    redirect('../login.php');
  };
?>