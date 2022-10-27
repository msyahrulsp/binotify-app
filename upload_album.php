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
  <link rel="stylesheet" type="text/css" href="css/upload_album.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
      echo 'Binotify · Tambah Album';
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
        <h1>Tambah Album</h1>
      </div>
      <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-wrapper">
        <div id="error-container">
        </div>
        <div class="input-container">
          <label>Judul</label>
          <input type="text" placeholder="Racing Into The Night"  name="judul" />
        </div>
        <div class="input-container">
          <label>Penyanyi</label>
          <input type="text" placeholder="Yoasobi" name="penyanyi" />
        </div>
        <div class="input-container">
          <label>Genre</label>
          <select name="genre" class="select-genre">
            <option value="Pop">Pop</option>
            <option value="Rock">Rock</option>
            <option value="Blues">Blues</option>
            <option value="Electronic">Electronic</option>
            <option value="Classic">Classic</option>
            <option value="Sedih">Sedih</option>
          </select>
        </div>
        <div class="input-container">
          <label>Image</label>
          <input type="file" accept="image/*" placeholder="Yoasobi" id="imageToUpload" name="imageToUpload" />
        </div>
        <div class="button-container">
          <input type="submit" value="Upload Album" name="upload-album">
        </div>
      </form>
    </div>
  </div>
</body>
</html>