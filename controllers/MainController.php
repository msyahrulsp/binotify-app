<?php
require('controllers/DBController.php');
require('controllers/SongController.php');
require('utils/dotenv.php');

// DOTENV
$dotenv = dotenv('./');

$host = getenv('DATABASE_HOST');
$dbname = getenv('DATABASE_NAME');
$user = getenv('DATABASE_USER');
$password = getenv('DATABASE_PASSWORD');

// Database Object
$db = new DBController($host, $dbname, $user, $password);

// Song Object
$song = new SongController($db);
