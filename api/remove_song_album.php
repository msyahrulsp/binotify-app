<?php
  require('../controllers/MainController.php');

  if ($_SERVER['REQUEST_METHOD'] == 'UPDATE') {
    $songId = $_GET['song_id'] ?? null;

    if (!$songId) {
      $res['status'] = 400;
      $res['message'] = 'Song ID tidak boleh kosong';
      echo json_encode($res);
      return;
    }

    try {
      $song->removeSongFromAlbum($songId);
      $res['status'] = 200;
      $res['message'] = 'Lagu berhasil dihapus dari album';
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