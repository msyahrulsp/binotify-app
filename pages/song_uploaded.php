<?php
require "../controllers/MainController.php";
$target_dir_song = "../assets/musics/";
$target_dir_image = "../assets/images/";
$target_file_song = $target_dir_song . basename($_FILES["songToUpload"]["name"]);
$target_file_image = $target_dir_image . basename($_FILES["imageToUpload"]["name"]);
$uploadOk = 1;
// $musicFileType = strtolower(pathinfo($target_file_song, PATHINFO_EXTENSION));

if (isset($_POST["submit"])) {
  $checkSong = filesize($_FILES["songToUpload"]["tmp_name"]);
  $checkImage = filesize($_FILES["imageToUpload"]["tmp_name"]);
  echo "ukuran file: " . $checkSong . $checkImage . '<br>';
  // foreach ($song as $s) {
  //   echo "<div><p>" . $s['judul'] . "</p></div>";
  // }
}
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
} else {
  if (move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song) && move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image)) {
    echo $_POST['judul'];
    echo "<br>";
    echo $_POST['penyanyi'];
    echo "<br>";
    echo $_POST['tanggal'];
    echo "<br>";
    echo $_POST['genre'];
    echo "<br>";
    echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
    echo "<br>";
    echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
    echo "<br>";
    echo "<img src=\"{$target_file_image}\" height=100 width=100>";
    echo "<audio controls>
<source src=\"{$target_file_song}\" type=\"audio/ogg\">
</audio>";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>