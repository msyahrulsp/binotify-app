<?php
  require 'controllers/MainController.php';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $album = new AlbumController($db);

    $lastAlbumId = $album->getLastAlbumID();
    $target_dir_image = "./assets/images/album/";
    $target_file_image = $target_dir_image  . $lastAlbumId+1 . '.' . pathinfo($_FILES['imageToUpload']['name'], PATHINFO_EXTENSION);

    $judul = $_POST['judul'];
    $penyanyi = $_POST['penyanyi'];
    $tanggal_terbit = date('Y-m-d');
    $total_duration = 0;
    $genre = $_POST['genre'];

    if (isset($_POST['upload-album'])) {
      $movedImage = move_uploaded_file($_FILES['imageToUpload']['tmp_name'], $target_file_image) ?? null;

      try {
        if ($movedImage) {
          $album->insertAlbum($judul, $penyanyi, $total_duration, $target_file_image, $tanggal_terbit, $genre);
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
    Judul:
    <input type="text" name="judul"><br>
    Penyanyi:
    <input type="text" name="penyanyi"><br>
    Genre:
    <input type="text" name="genre"><br>
    Image:
    <input type="file" name="imageToUpload" id="imageToUpload" accept="image/*"><br>
    <div>TBD Input song kudu gimana</div>
    <input type="submit" value="Upload Album" name="upload-album">
</body>
</html>