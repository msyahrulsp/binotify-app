<?php
  require('controllers/MainController.php');
  $isAdmin = $_SESSION['isAdmin'] ?? null;
  $album = new AlbumController($db);
  $albumData = $album->getSingleAlbum($_GET['album_id']);
  $albumSong = $album->getAlbumSong($_GET['album_id']);

  $judul = $albumData['judul'] ?? null;
  $penyanyi = $albumData['penyanyi'] ?? null;
  $total_duration = $albumData['total_duration'] ?? null;
  $image_path = $albumData['image_path'] ?? null;
  $tanggal_terbit = $albumData['tanggal_terbit'] ?? null;
  $genre = $albumData['genre'] ?? null;

  function convertSecToFullTime($duration) {
    $temp = gmdate("H:i:s", $duration);
    $temp = explode(':', $temp);
    $dur = '';
    if ($temp[0] != '00') {
      $dur .= ltrim($temp[0], '0') . ' jam ';
    }
    if ($temp[1] != '00') {
      $dur .= ltrim($temp[1], '0') . ' menit ';
    }
    if ($temp[2] != '00') {
      $dur .= ltrim($temp[2], '0'). ' detik';
    }
    return $dur;
  }

  function echoEditAlbum($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong) {
    $isAdmin = $_SESSION['isAdmin'] ?? false;
    $total_time = convertSecToFullTime($total_duration);
    $qty = count($albumSong);
    $index = 1;
    $songList = '';
    foreach ($albumSong as $song) {
      $songList .= "
        <div class=\"song-container\">
          <div class=\"song-title\">
            <p class=\"order\">$index</p>
            <div class=\"title\">
              <a>{$song['judul']}</a>
              <p class=\"song-artist\">{$song['penyanyi']}</p>
            </div>
          </div>
          <button class=\"song-remove\" onClick='removeSong({$song['song_id']})'>
            Hilangkan Lagu
          </button>
        </div>
      ";
      $index++;
    }
    $html = <<<"EOT"
      <div class="edit-container">
        <div class="edit-header">
          <img src=$image_path height="200" alt="cover" />
          <div class="edit-header-info">
            <h5>ALBUM</h5>
            <h1>$judul</h1>
            <div class="edit-header-info-inline">
              <p class="penyanyi">$penyanyi · </p>
              <p>$tanggal_terbit · </p>
              <p>$qty,
                <p class="duration">
                  $total_time
                </p>
              </p>
            </div>
            <div class='btn-container'>
              <a href='/album.php?album_id={$_GET['album_id']}'>
                <button class='btn green'>  
                  Back
                </button>
              </a>
              <div>
                <button class='btn red' onClick='deleteAlbum({$_GET['album_id']})'>
                  Hapus Album
                </button>
              </div>
            </div>
          </div>
        </div>
        <form method="POST" id="form-upload" action="" class="form-wrapper">
          <div id="error-container">
          </div>
          <div class="input-container">
            <label>Judul</label>
            <input type="text" placeholder="Racing Into The Night" name="judul" value="{$judul}" />
          </div>
          <div class="input-container">
            <label>Genre</label>
            <input type="text" placeholder="Pop" name="genre" value="{$genre}" />
          </div>
          <div class="input-container">
            <label>Tanggal Terbit</label>
            <input type="date" name="tanggal_terbit" value={$tanggal_terbit} />
          </div>
          <div class="input-container">
            <label>Image</label>
            <input type="file" accept="image/*" id="imageToUpload" name="imageToUpload" />
          </div>
          <div class="button-container">
            <button class="form-button" type="submit" name="upload-album">Save</button>
          </div>
        </form>
        <div class="song-wrapper">
          $songList
        </div>
      </div>
    EOT;
    echo $html; 
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="stylesheet" type="text/css" href="css/edit_album.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
      echo 'Edit ' . $judul . ' · ' . $penyanyi;
    ?>
  </title>
</head>
<body>
  <div class="container">
    <?php
      include('templates/navbar.php');
      echoEditAlbum($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong);
    ?>
  </div>
  <script>
    function deleteAlbum(album_id) {
      const formData = new FormData();
      formData.append('album_id', album_id);
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          const res = this.responseText.includes("<br>") ? 
            this.responseText.split("<br>")[1] : this.responseText;
          const response = JSON.parse(res);
          if (response.status == 200) {
            alert(response.message);
            window.location.href = '/album_list.php';
          } else {
            alert(response.message);
          }
        }
      };
      xhttp.open("DELETE", `/api/delete_album.php?album_id=${album_id}`, true);
      xhttp.send(formData);
    }

    function removeSong(song_id) {
      console.log(song_id);
    }
  </script>
</body>
</html>