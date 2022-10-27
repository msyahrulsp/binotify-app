<?php
  require('controllers/MainController.php');
  $album = new AlbumController($db);
  $albumData = $album->getSingleAlbum($_GET['album_id']);
  $albumSong = $album->getAlbumSong($_GET['album_id']);
  
  $judul = $albumData['judul'] ?? null;
  $penyanyi = $albumData['penyanyi'] ?? null;
  $total_duration = $albumData['total_duration'] ?? null;
  $image_path = $albumData['image_path'] ?? null;
  $tanggal_terbit = $albumData['tanggal_terbit'] ?? null;
  $genre = $albumData['genre'] ?? null;
  
  function echoAlbumDetail($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong) {
    $isAdmin = $_SESSION['isAdmin'] ?? false;
    $minutes = floor($total_duration / 60) . " menit";
    $seconds = $total_duration % 60 == 0 ? null : $total_duration % 60 . " detik";
    $qty = count($albumSong);
    $songList = "
      <div class=\"song-container w-border\">
        <div class=\"song-title\">
          <p class=\"order special\">#</p>
          <p class=\"title special\">Judul</p>
        </div>
        <p class=\"song-duration special\">Durasi</p>
      </div>
    ";
    $index = 1;
    foreach ($albumSong as $song) {
      $dur = floor($song['duration'] / 60) . ":" . $song['duration'] % 60;
      $songList .= "
        <div class=\"song-container\">
          <div class=\"song-title\">
            <p class=\"order\">$index</p>
            <p class=\"title\">
              <a href=\"song.php?song_id={$song['song_id']}\">
                {$song['judul']}
              </a>
            </p>
          </div>
          <p class=\"song-duration\">$dur</p>
        </div>
      ";
      $index++;
    }
    $html = <<<"EOT"
      <div class="album-container">
        <div class="album-header">
          <img src=$image_path height="200" alt="cover" />
          <div class="album-header-info">
            <h5>ALBUM</h5>
            <h1>$judul</h1>
            <div class="album-header-info-inline">
              <p class="penyanyi">$penyanyi · </p>
              <p>$tanggal_terbit · </p>
              <p>$qty,
                <p class="duration">
                  $minutes $seconds
                </p>
              </p>
              
            </div>
          </div>
        </div>
        <div class="album-song">
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
  <link rel="stylesheet" type="text/css" href="css/album.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <?php
      include('templates/navbar.php');
      echoAlbumDetail($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong);
    ?>
  </div>
</body>
</html>