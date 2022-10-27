<?php
require 'controllers/MainController.php';

$isAdmin = true;

if (!$isAdmin) {
  echo "<script>
alert('Unauthorized access.');
window.location.href='/';
</script>";
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
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/upload_song.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
    echo 'Binotify · Tambah Lagu';
    ?>
  </title>

</head>

<body>
  <div class="container">
    <?php
    include('templates/navbar.php');
    ?>
    <div class="form-container">
      <div class="form-header">
        <h1>Tambah Lagu</h1>
      </div>
      <form id="upload-form" method="post" enctype="multipart/form-data" class="form-wrapper">
        <div class="input-container">
          <label>Judul:</label>
          <input type="text" name='judul'>
        </div>
        <div class="input-container">
          <label>Penyanyi:</label>
          <input type="text" name='penyanyi'>
        </div>
        <div class="input-container">
          <label>Tanggal:</label>
          <input type="date" name='tanggal'>
        </div>
        <div class="input-container">
          <label>Genre:</label>
          <input type="text" name='genre'>
        </div>

        <div class="input-container">
          <label>File lagu:</label>
          <input type="file" name="songToUpload" id="songToUpload" accept="audio/*">
        </div>
        <div class="input-container">
          <label>File gambar:</label>
          <input type="file" name="imageToUpload" id="imageToUpload" accept="image/*">
        </div>


        <div class="input-container">
          <label>Album:</label>
          <label>TBD s input selection for available album</label>
        </div>

        <input type="text" hidden name="duration" id="duration">
        <div class="button-container">
          <button class="form-button" onClick="uploadSong()" value="Upload Song" name="upload-song">Upload Song</button>
        </div>

      </form>
    </div>
  </div>

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