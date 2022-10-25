<?php
define('ROOT_DIR', dirname(__FILE__) . '/../');
require(ROOT_DIR . 'controllers/DBController.php');
require(ROOT_DIR . 'controllers/SongController.php');
require(ROOT_DIR . 'controllers/UserController.php');
require(ROOT_DIR . 'utils/dotenv.php');

// DOTENV
$dotenv = dotenv(ROOT_DIR . '.env');

$host = getenv('DATABASE_HOST');
$dbname = getenv('DATABASE_NAME');
$user = getenv('DATABASE_USER');
$password = getenv('DATABASE_PASSWORD');
// echo $host . $dbname . $user . $password;
// Database Object
$db = new DBController($host, $dbname, $user, $password);

// Song Object
$song = new SongController($db);

// Redirect function
function redirect($url) {
  header('Location: ' . $url);
  die();
}