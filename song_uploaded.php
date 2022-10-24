<?php
require "controllers/MainController.php";

$lastSongID = $_POST['lastSongID'];
echo '<br>last song id' . $lastSongID . '<br>';

$newSongID = (int)$lastSongID + 1;

$target_dir_song = "./assets/musics/";
$target_dir_image = "./assets/images/";
$target_file_song = $target_dir_song . 'song' . $newSongID . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION);
$target_file_image = $target_dir_image . 'image' . $newSongID . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION);
$uploadOk = 1;
// $musicFileType = strtolower(pathinfo($target_file_song, PATHINFO_EXTENSION));

// payload
$judul = $_POST['judul'];
$penyanyi = $_POST['penyanyi'];
$tanggal = $_POST['tanggal'];
$genre = $_POST['genre'];

if (isset($_POST["submit"])) {
  echo '$target_file_song ' . $target_file_song;
  echo '<br>';
  echo '$target_file_image ' . $target_file_image;
  echo '<br>';
  // echo 'song <br>';
  // print_r($_FILES["songToUpload"]["tmp_name"]);
  // echo 'image <br>';
  // print_r($_FILES["imageToUpload"]["tmp_name"]);
  // foreach ($song as $s) {
  //   echo "<div><p>" . $s['judul'] . "</p></div>";
  // } 
}
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
} else {
  echo 'song <br>';
  print_r($_FILES["songToUpload"]["tmp_name"]);
  echo 'image <br>';
  print_r($_FILES["imageToUpload"]["tmp_name"]);
  // echo '<br>move song' . move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
  // echo '<br>move image' . move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

  try {
    if (move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song) && move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image)) {
      echo '1 <br>';
      $song->insertSong($judul, $penyanyi, $tanggal, $genre, 10, $target_file_song, $target_file_image);
      echo '2 <br>';
      echo "--------------------------------------";
      echo "<br>";
      echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
      echo "<br>";
      echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
