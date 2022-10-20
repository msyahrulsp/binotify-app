<?php

  // Penggunaan dotenv
  // START
  require './utils/dotenv.php';
  $dotenv = dotenv('./');

  echo getenv('DATABASE_HOST');
  // END
?>