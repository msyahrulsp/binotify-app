<?php
  require('controllers/MainController.php');
  $album = new AlbumController($db);
  $albumList = $album->getAllAlbum();
  
  function echoAlbum($albumList) {
    $albumListDiv = "
      <div class=\"album-list\">
        <div class=\"album-title\">
          <p class=\"cover special\">Cover</p>
          <p class=\"title special\">Judul</p>
        </div>
        <div class=\"album-util\">
          <p class=\"genre special\">Genre</p>
          <p class=\"date special\">Tahun Terbit</p>
        </div>
      </div>
    ";
    foreach ($albumList as $album) {
      $year = explode('-', $album['tanggal_terbit']);
      $albumListDiv .= "
        <a href=\"album.php?album_id={$album['album_id']}\">     
          <div class=\"album-list\">
            <div class=\"album-title\">
              <img src=\"{$album['image_path']}\" width=\"100\" height=\"100\" alt=\"cover\" />
              <div class=\"title\">
                <p class=\"album-name\">{$album['judul']}</p>
                <p class=\"album-artist special\">{$album['penyanyi']}</p>
              </div>
            </div>
            <div class=\"album-util\">
              <p class=\"genre special\">{$album['genre']}</p>
              <p class=\"date special\">$year[0]</p>
            </div>
          </div>
        </a>
      ";
    }
    $html = <<<"EOT"
      <div class="album-container">
        <div class="album-header">
          <h1>Daftar Album</h1>
        </div>
        <div class="album-list-container">
          $albumListDiv
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
  <link rel="stylesheet" type="text/css" href="css/album_list.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
      echo 'Binotify · Daftar Album';
    ?>
  </title>
</head>
<body>
  <div class="container">
    <?php
      include('templates/navbar.php');
      echoAlbum($albumList);
    ?>
  </div>
</body>
</html>