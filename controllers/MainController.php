<?php
require('DBController.php');
require('SongController.php');
require('utils/dotenv.php');

// DOTENV
$dotenv = dotenv('./');

$host = getenv('DATABASE_HOST');
$dbname = getenv('DATABASE_NAME');
$user = getenv('DATABASE_USER');
$password = getenv('DATABASE_PASSWORD');
echo $host . $dbname . $user . $password;
// Database Object
$db = new DBController($host, $dbname, $user, $password);

// Song Object
$song = new SongController($db);
