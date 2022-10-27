<?php
  require('../controllers/MainController.php');

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $album = new AlbumController($db);

    $lastAlbumId = $album->getLastAlbumID();
    $target_dir_image = "../assets/images/album/";
    $target_file_image = $target_dir_image  . $lastAlbumId+1 . '.' . pathinfo($_FILES['imageToUpload']['name'], PATHINFO_EXTENSION);

    $judul = $_POST['judul'] ?? null;
    $penyanyi = $_POST['penyanyi'] ?? null;
    $tanggal_terbit = $_POST['tanggal_terbit'] ?? null;
    $total_duration = 0;
    $genre = $_POST['genre'] ?? null;
    $res = null;
    $ext = array("jpg", "jpeg", "png");

    if (isset($_POST)) {
      if (!$judul || !$penyanyi || !$tanggal_terbit || !$genre) {
        $res['status'] = 400;
        $res['message'] = 'Lengkapi semua field';
        echo json_encode($res);
        return;
      }

      if (!in_array(pathinfo($_FILES['imageToUpload']['name'], PATHINFO_EXTENSION), $ext)) {
        $res['status'] = 400;
        $res['message'] = 'File yang diupload harus berupa gambar (.jpg, .jpeg, .png)';
        echo json_encode($res);
        return;
      }
      $movedImage = move_uploaded_file($_FILES['imageToUpload']['tmp_name'], $target_file_image) ?? null;

      try {
        if ($movedImage) {
          $album->insertAlbum($judul, $penyanyi, $total_duration, $target_file_image, $tanggal_terbit, $genre);
          $res['status'] = 200;
          $res['message'] = 'Album berhasil ditambahkan';
          echo json_encode($res);
          return;
        }
      } catch (PDOException $e) {
        $res['status'] = 500;
        $res['message'] = $e->getMessage();
        echo json_encode($res);
        return;
      }
    }
  }
?>