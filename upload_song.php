<?php
require 'controllers/MainController.php';
$songData = $song->getSongs();

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
  $duration = $_POST['duration'];

  if (isset($_POST["upload-song"])) {
    $movedSong = move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song) ?? null;
    $movedImage = move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image) ?? null;

    if ($movedSong && $movedImage) {
      $song->insertSong($judul, $penyanyi, $tanggal, $genre, $duration, $target_file_song, $target_file_image);
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
  <form id="upload-form" method="post" enctype="multipart/form-data">
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
    <input type="text" hidden name="duration" id="duration">
    <button onClick="uploadSong()" value="Upload Song" name="upload-song">Upload Song</button>
  </form>
</body>
<script>
  document.getElementById("songToUpload").addEventListener('change', function() {
    const file = this.files[0];
    console.log('path', file)
    const reader = new FileReader();
    reader.onload = function(event) {
      const audioContext = new(window.AudioContext || window.webkitAudioContext)();
      audioContext.decodeAudioData(event.target.result, function(buffer) {
        const duration = buffer.duration;
        document.getElementById("duration").value = parseInt(duration)
      })
    }
    reader.onerror = function(event) {
      console.error("Error saat membaca file.", event);
    };

    reader.readAsArrayBuffer(file);
  });
</script>

</html>