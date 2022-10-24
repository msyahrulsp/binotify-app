<?php
require "controllers/MainController.php";
$songID = $_POST['song_id'];
echo 'song id'.$songID;
$songData = $song->getSingleSong($songID);
print_r($songData);
$target_dir_song = "./assets/musics/";
$target_dir_image = "./assets/images/";
$songFile = $_FILES["songToUpload"]["name"];
$imageFile = $_FILES["imageToUpload"]['name'];



// null diganti current path
$target_file_song = $songFile ? $target_dir_song . 'song' . $newSongID . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
$target_file_image = $imageFile ? $target_dir_image . 'image' . $newSongID . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
$uploadOk = 1;
// $musicFileType = strtolower(pathinfo($target_file_song, PATHINFO_EXTENSION));

// payload
$judul = $_POST['judul'];
$penyanyi = $_POST['penyanyi'];
$tanggal = $_POST['tanggal'];
$genre = $_POST['genre'];

if (isset($_POST["submit"])) {
  echo '$target_file_song' . $target_file_song;
  echo '<br>$target_file_image' . $target_file_image;
  // foreach ($song as $s) {
  //   echo "<div><p>" . $s['judul'] . "</p></div>";
  // } 
}
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
} else {
  echo '<br>move song' . move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
  echo '<br>move image' . move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

  try {
    if ($songFile && $imageFile) {
      unlink($target_file_song);
      unlink($target_file_image);
      move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

      echo "--------------------------------------";
      echo "<br>";
      echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
      echo "<br>";
      echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
    } elseif ($songFile) {
      unlink($target_file_song);
      move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
      echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
    } elseif ($imageFile) {
      unlink($target_file_image);
      move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);
      echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
    } else{
      echo 'song updated';
    }
    $song->updateSong($judul, $penyanyi, $tanggal, $genre, '10', $target_file_song, $target_file_image, $songID);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
