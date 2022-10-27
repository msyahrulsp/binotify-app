<?php
  require('controllers/MainController.php');
  session_start();
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

  function convertSecToTime($duration) {
    $temp = gmdate("i:s", $duration);
    $temp = explode(":", $temp);
    $dur = '';
    if ($temp[0] != '00') {
      $dur .= ltrim($temp[0], '0') . ':';
    } else {
      $dur .= '0:';
    }
    if ($temp[1] != '00') {
      $dur .= $temp[1];
    }
    return $dur;
  }
  
  function echoAlbumDetail($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong) {
    $isAdmin = $_SESSION['isAdmin'] ?? false;
    $total_time = convertSecToFullTime($total_duration);
    $qty = count($albumSong);
    // TODO: access
    $editButton = !$isAdmin ? "
      <div class='edit-btn'>
        <a href='/edit_album.php?album_id={$_GET['album_id']}'>
          <button class='btn'>
            Edit
          </button>
        </a>
      </div>
    " : null;
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
      $dur = convertSecToTime($song['duration']);
      $songList .= "
        <div class=\"song-container\">
          <div class=\"song-title\">
            <p class=\"order\">$index</p>
            <div class=\"title\">
              <a href=\"song.php?song_id={$song['song_id']}\">
                {$song['judul']}
              </a>
              <p class=\"song-artist\">{$song['penyanyi']}</p>
            </div>
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
                  $total_time
                </p>
              </p>
            </div>
            $editButton
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
  <title>
    <?php
      echo $judul . ' · ' . $penyanyi;
    ?>
  </title>
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