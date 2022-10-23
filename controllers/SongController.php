<?php

class SongController
{
  public $db = null;

  public function __construct(DBController $db)
  {
    if (!isset($db->con)) return null;
    $this->db = $db;
  }


  public function getSong()
  // fetch songs
  {
    if (isset($this->db->con)) {
      $stmt = $this->db->con->prepare("SELECT * FROM `song`");
      $stmt->execute();
      $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $songs;
    } else {
      echo 'db not set';
    }
  }

  public function insertSong($judul, $penyanyi, $tanggal, $genre, $durasi, $audio_path, $image_path)
  {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
      VALUES (:judul, :penyanyi, :tanggal, :genre, 10, :audio_path, :image_path, NULL)";
      $this->db->con->prepare($sql)->execute(array(
        ':judul' => $judul,
        ':penyanyi' => $penyanyi,
        ':tanggal' => $tanggal,
        ':genre' => $genre,
        ':audio_path' => $audio_path,
        ':image_path' => $image_path
      ));
      echo "New record created successfully<br>";
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function getSingleSong($songId)
  {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM song WHERE song_id={$songId}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $song = $stmt->fetch();
      return $song;
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function echoSongDetail($judul = 'ini judul', $penyanyi = 'ini penyanyi', $tanggal = 'ini tanggal', $genre = 'ini genre', $durasi = 'ini durasi', $imagePath = 'assets/images/defaultImage.jpg', $songPath = '', $tombol_album = 'ini tombol album')
  {
    $html = <<<"EOT"
    <div>
      <p>$judul</p>
      <p>$penyanyi</p>
      <p>$tanggal</p>
      <p>$genre</p>
      <p>$durasi</p>
      <img src="{$imagePath}" height=100 width=100>
    <audio controls>
      <source src="{$songPath}" type="audio/ogg" />
    </audio>
      <p>$tombol_album</p>
    </div>
    EOT;
    echo $html;
  }
}
