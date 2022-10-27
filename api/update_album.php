<?php
  require('../controllers/MainController.php');

  if ($_SERVER['REQUEST_METHOD'] == 'UPDATE') {
    $album = new AlbumController($db);
    $albumId = $_GET['album_id'] ?? null;
    $judul = $_POST['judul'] ?? null;
    $genre = $_POST['genre'] ?? null;
    $tanggal_terbit = $_POST['tanggal_terbit'] ?? null;
    $image_path = $_POST['image_path'] ?? null;

    if (!$albumId) {
      $res['status'] = 400;
      $res['message'] = 'Album ID tidak boleh kosong';
      echo json_encode($res);
      return;
    }

    try {
      $album->updateAlbum($albumId, $judul, $genre, $tanggal_terbit, $image_path);
      $res['status'] = 200;
      $res['message'] = 'Album berhasil diupdate';
      echo json_encode($res);
      return;
    } catch (PDOException $e) {
      $res['status'] = 500;
      $res['message'] = $e->getMessage();
      echo json_encode($res);
      return;
    }
  }
?>