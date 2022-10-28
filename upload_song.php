<?php
require 'controllers/MainController.php';
$curDate = date('Y-m-d');
$albums = $album->getAllAlbums();

$isAdmin = $_SESSION['isAdmin'] ?? false;

if (!$isAdmin) {
  echo "<script>
alert('Unauthorized access.');
window.location.href='/';
</script>";
}

$listSelect = null;

if (count($albums) > 0) {
  echo '<br>masuk';
  $listSelect = "
    <div class='valid-album-wrapper' id='dropdown-wrapper'>
      <select name='songAlbum' id='songAlbum' class='album-select'>
        <option selected='selected' value=0>Pilih Album</option>
  ";

  foreach ($albums as $song) {
    $listSelect .= "
      <option value={$song['album_id']} id={$song['album_id']}>{$song['judul']}</option>
    ";
  }

  $listSelect .= "
          </option>
        </select>
    </div>
    ";
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
      <form id="upload-form" method="POST" enctype="multipart/form-data" class="form-wrapper" action="">
        <div class="input-container">
          <label>Judul</label>
          <input type="text" placeholder="Racing Into The Night" name='judul'>
        </div>
        <div class="input-container">
          <label>Penyanyi</label>
          <input type="text" placeholder="Yoasobi" name='penyanyi'>
        </div>
        <div class="input-container">
          <label>Genre</label>
          <input type="text" placeholder="Pop" name='genre'>
        </div>
        <div class="input-container">
          <label>Tanggal</label>
          <input type="date" name='tanggal' value=<?php echo $curDate ?> />
        </div>
        <div class="input-container">
          <label>File lagu</label>
          <input type="file" name="songToUpload" id="songToUpload" accept="audio/*">
        </div>
        <div class="input-container">
          <label>File gambar</label>
          <input type="file" name="imageToUpload" id="imageToUpload" accept="image/*">
        </div>
        <div class="input-container">
          <label>Album</label>
          <?php echo $listSelect ?>
        </div>
        <input type="text" hidden name="duration" id="duration">
        <div class="button-container">
          <button type="submit" class="form-button" value="Upload Song" name="upload-song">Upload Song</button>
        </div>
        <div id="error-container">
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

  document.getElementById("upload-form").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById("upload-form"));
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState === 4 && this.status === 200) {
        console.log('res', this.responseText)
        const res = this.responseText.includes("<br>") ?
          this.responseText.split("<br>")[1] : this.responseText;
        const response = JSON.parse(res);

        if (response.status === 200) {
          alert(response.message);
          document.getElementById("upload-form").reset();
        } else {
          let errorElmt = document.getElementById("error-container");
          if (errorElmt.style.visibility === 'hidden') {
            errorElmt.style.visibility = 'visible';
          }
          errorElmt.innerHTML = `
              <div class="error-message">
                <p>${response.message}</p>
              </div>
            `;
          setTimeout(function() {
            errorElmt.style.visibility = 'hidden';
          }, 3000)
        }
      }
    };
    xhttp.open("POST", "api/upload_song.php", true);
    xhttp.send(formData);
  })
</script>

</html>