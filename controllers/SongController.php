<?php

class SongController
{
  public $db = null;

  public function __construct(DBController $db)
  {
    if (!isset($db->con)) return null;
    $this->db = $db;
  }

  public function getSongs()
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
      $sql = "INSERT INTO song (song_id, judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
      VALUES (:song_id, :judul, :penyanyi, :tanggal, :genre, 10, :audio_path, :image_path, 1)";
      $this->db->con->prepare($sql)->execute(array(
        ':song_id' => null,
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

  public function updateSong($judul, $penyanyi, $tanggal, $genre, $durasi, $audio_path, $image_path, $song_id)
  {
    try {
      // updateSong($judul, $penyanyi, $tanggal, $genre, '10', $target_file_song, $target_file_image, $songID);

      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE song SET song_id=null,judul=:judul, penyanyi=:penyanyi, tanggal_terbit=:tanggal, genre=:genre, duration=:duration, audio_path=:audio_path, image_path=:image_path, album_id=:album_id WHERE song_id=:song_id";
      $this->db->con->prepare($sql)->execute(array(
        'judul' => $judul,
        'penyanyi' => $penyanyi,
        'tanggal' => $tanggal,
        'genre' => $genre,
        'audio_path' => $audio_path,
        'image_path' => $image_path,
        'duration' => 10,
        'album_id' => 0,
        'song_id' => $song_id,
      ));
      echo "New record created successfully<br>";
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function getSingleSong($songID)
  {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM song WHERE song_id={$songID}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $song = $stmt->fetch();
      return $song;
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function getLastSongID() {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM song ORDER BY song_id DESC LIMIT 1";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $lastSongID = $stmt->fetch();
      return $lastSongID['song_id'];
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }


}
