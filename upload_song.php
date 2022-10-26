<?php
require 'controllers/MainController.php';
$songData = $song->getSongs();

$test = "PIE";
// FIXME: ini test buat upload song ke album
if ($test === "PIE") {
  $html = "<h1>PIE</h1>";
} else {
  $html = "<h1>NOT PIE</h1>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $lastSongID = $song->getLastSongID();

  $target_dir_song = "./assets/musics/";
  $target_dir_image = "./assets/images/";
  $target_file_song = $target_dir_song . 'song' . $lastSongID . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION);
  $target_file_image = $target_dir_image . 'image' . $lastSongID . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION);

  // payload
  $judul = $_POST['judul'];
  $penyanyi = $_POST['penyanyi'];
  $tanggal = $_POST['tanggal'];
  $genre = $_POST['genre'];

  if (isset($_POST["upload-song"])) {
    $movedSong = move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song) ?? null;
    $movedImage = move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image) ?? null;

    try {
      if ($movedSong && $movedImage) {
        $song->insertSong($judul, $penyanyi, $tanggal, $genre, 10, $target_file_song, $target_file_image);
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form method="post" enctype="multipart/form-data">
    <!-- auto count duration -->
    Judul:
    <input type="text" name='judul'><br>
    Penyanyi:
    <input type="text" name='penyanyi'><br>
    Tanggal:
    <input type="date" name='tanggal'><br>
    Genre:
    <input type="text" name='genre'><br>
    File lagu:
    <input type="file" name="songToUpload" id="songToUpload" accept="audio/*"><br>
    File gambar:
    <input type="file" name="imageToUpload" id="imageToUpload" accept="image/*"><br>
    Album:
    <span>TBD s input selection for available album</span><br>
    <button type="submit" value="Upload Song" name="upload-song">Upload Song</button>
    <?php
      echo $html;
    ?>
  </form>
</body>

</html>