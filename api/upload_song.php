<?php
  require('../controllers/MainController.php');

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST)) {
      // payload
      $judul = $_POST['judul'] ?? null;
      $penyanyi = $_POST['penyanyi'] ?? null;
      $tanggal = $_POST['tanggal'] ?? null;
      $genre = $_POST['genre'] ?? null;
      $duration = $_POST['duration'] ?? null;
      $res = null;
      $songExt = array("mp3");
      $imageExt = array("jpg", "jpeg", "png");

      if (!$judul || !$penyanyi || !$genre || !$tanggal || !$_FILES["songToUpload"]["name"] || !$_FILES["imageToUpload"]["name"]) {
        $res['status'] = 400;
        $res['message'] = 'Lengkapi semua field';
        echo json_encode($res);
        return;
      }

      if (!in_array(pathinfo($_FILES['songToUpload']['name'], PATHINFO_EXTENSION), $songExt) && !in_array(pathinfo($_FILES['imageToUpload']['name'], PATHINFO_EXTENSION), $imageExt)) {
        $res['status'] = 400;
        $res['message'] = "File lagu yang diupload harus berupa (.mp3) dan File gambar yang diupload harus berupa (.jpg, .jpeg, .png)";
        echo json_encode($res);
        return;
      }

      if (!in_array(pathinfo($_FILES['songToUpload']['name'], PATHINFO_EXTENSION), $songExt)) {
        $res['status'] = 400;
        $res['message'] = 'File lagu yang diupload harus berupa (.mp3)';
        echo json_encode($res);
        return;
      }
  
      if (!in_array(pathinfo($_FILES['imageToUpload']['name'], PATHINFO_EXTENSION), $imageExt)) {
        $res['status'] = 400;
        $res['message'] = 'File gambar yang diupload harus berupa (.jpg, .jpeg, .png)';
        echo json_encode($res);
        return;
      }
  
      $lastSongID = $song->getLastSongID();
  
      $target_dir_song = "../assets/musics/";
      $target_dir_image = "../assets/images/";
      $target_file_song = $target_dir_song . 'song' . $lastSongID . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION);
      echo '$target_file_song'.$target_file_song;
      $target_file_image = $target_dir_image . 'image' . $lastSongID . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION);
      echo '$target_file_image'.$target_file_image;
  
      $movedSong = move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song) ?? null;
      $movedImage = move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image) ?? null;
  
      try {
        if ($movedSong && $movedImage) {
          $song->insertSong($judul, $penyanyi, $tanggal, $genre, $duration, $target_file_song, $target_file_image, null);
          $res['status'] = 200;
          $res['message'] = 'Lagu berhasil ditambahkan';
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