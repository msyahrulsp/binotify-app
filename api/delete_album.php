<?php
  require('../controllers/MainController.php');

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo file_get_contents('php://input');
    $album = new AlbumController($db);
    $albumId = $_GET['album_id'] ?? null;
    $res = null;

    if (!$albumId) {
      $album->deleteAlbum($albumId);
      $res['status'] = 400;
      $res['message'] = 'Album ID kosong';
      echo json_encode($res);
      return;
    }

    try {
      $song->removeAlbum($albumId);
    } catch (Exception $e) {
      $res['status'] = 500;
      $res['message'] = 'Terjadi kesalahan pada server';
      echo json_encode($res);
      return;
    }

    try {
      $album->deleteAlbum($albumId);
    } catch (Exception $e) {
      $res['status'] = 500;
      $res['message'] = 'Terjadi kesalahan pada server';
      echo json_encode($res);
      return;
    }

    $res['status'] = 200;
    $res['message'] = 'Album berhasil dihapus';
    echo json_encode($res);
  }
?>