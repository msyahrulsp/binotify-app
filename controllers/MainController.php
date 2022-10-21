<?php
require('controllers/DBController.php');
require('controllers/SongController.php');

// Database Object
$db = new DBController();

// Song Object
$song = new SongController($db);
