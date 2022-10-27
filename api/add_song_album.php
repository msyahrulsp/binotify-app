<?php
  require('../controllers/MainController.php');

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $songId = $_POST['song_id'] ?? null;
    $albumId = $_POST['album_id'] ?? null;

    if (isset($_POST)) {
      if (!$songId) {
        $res['status'] = 400;
        $res['message'] = 'Song ID tidak boleh kosong';
        echo json_encode($res);
        return;
      }

      try {
        $song->addSongToAlbum($songId, $albumId);
        $res['status'] = 200;
        $res['message'] = 'Lagu berhasil ditambah ke album';
        $res['data'] = $song->getSingleSong($songId);
        echo json_encode($res);
        return;
      } catch (PDOException $e) {
        $res['status'] = 500;
        $res['message'] = $e->getMessage();
        echo json_encode($res);
        return;
      }
    }
  }
?>