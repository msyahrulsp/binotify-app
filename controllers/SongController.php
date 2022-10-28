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

  public function getHomePageSongs()
  {
    if (isset($this->db->con)) {
      $stmt = $this->db->con->prepare("SELECT * FROM song ORDER BY judul LIMIT 10");
      $stmt->execute();
      $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $songs;
    } else {
      echo 'db not set';
    }
  }
  public function insertSong($judul, $penyanyi, $tanggal, $genre, $durasi, $audio_path, $image_path, $albumID)
  {
    $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
      VALUES (:judul, :penyanyi, :tanggal, :genre, :duration, :audio_path, :image_path, :albumID);
      UPDATE album SET total_duration=total_duration+:duration WHERE album_id=:albumID";
    $this->db->con->prepare($sql)->execute(array(
      ':judul' => $judul,
      ':penyanyi' => $penyanyi,
      ':tanggal' => $tanggal,
      ':genre' => $genre,
      ':duration' => $durasi,
      ':audio_path' => $audio_path,
      ':image_path' => $image_path,
      ':albumID' => $albumID
    ));
    echo "New record created successfully<br>";
  }

  public function updateSong($judul, $penyanyi, $tanggal, $genre, $durasi, $audio_path, $image_path, $song_id, $albumID)
  {
    try {
      // updateSong($judul, $penyanyi, $tanggal, $genre, '10', $target_file_song, $target_file_image, $songID);

      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE song SET judul=:judul, penyanyi=:penyanyi, tanggal_terbit=:tanggal, genre=:genre, duration=:duration, audio_path=:audio_path, image_path=:image_path, album_id=:album_id WHERE song_id=:song_id";
      $this->db->con->prepare($sql)->execute(array(
        'judul' => $judul,
        'penyanyi' => $penyanyi,
        'tanggal' => $tanggal,
        'genre' => $genre,
        'audio_path' => $audio_path,
        'image_path' => $image_path,
        'duration' => $durasi,
        'album_id' => $albumID,
        'song_id' => $song_id,
      ));
      echo '<br> received judul' . $judul;
      echo "New record edited successfully<br>";
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

  public function getLastSongID()
  {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT AUTO_INCREMENT
      FROM  INFORMATION_SCHEMA.TABLES
      WHERE TABLE_SCHEMA = 'binotify'
      AND   TABLE_NAME   = 'song'";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $lastSongID = $stmt->fetch();
      return $lastSongID['AUTO_INCREMENT'];
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function countSongs($keyword, $genre) {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT count(*) AS total_songs FROM song WHERE judul LIKE '%{$keyword}%' AND genre LIKE '%{$genre}%'";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $total_songs = $stmt->fetch(PDO::FETCH_ASSOC);
      return $total_songs['total_songs'];
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function searchSongs($keyword, $genre, $page, $limit, $sort, $order_type) {
    $page = $page - 1;
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * from song WHERE (judul LIKE '%{$keyword}%' OR penyanyi LIKE '%{$keyword}%' OR tanggal_terbit LIKE '%{$keyword}%') AND genre LIKE '%{$genre}%' ORDER BY {$sort} {$order_type} LIMIT {$page}, {$limit}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $songs;
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }
  
  public function deleteSong($songID,$duration,$albumID)
  {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "DELETE FROM song WHERE song_id=$songID;
      UPDATE album SET total_duration=total_duration-$duration WHERE album_id=$albumID";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function removeAlbum($album_id) {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE song SET album_id=NULL WHERE album_id={$album_id}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function getValidSong($penyanyi) {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM song WHERE album_id IS NULL AND LOWER(penyanyi) = LOWER('{$penyanyi}')";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $songs;
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function getGenre() {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT DISTINCT genre from song";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
      $genre = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $genre;
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function addSongToAlbum($song_id, $album_id) {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE album SET total_duration=total_duration+(SELECT duration FROM song WHERE song_id={$song_id}) WHERE album_id={$album_id}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();

      $sql = "UPDATE song SET album_id={$album_id} WHERE song_id ={$song_id}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  public function removeSongFromAlbum($song_id, $album_id) {
    try {
      $this->db->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE album SET total_duration=total_duration-(SELECT duration FROM song WHERE song_id={$song_id}) WHERE album_id={$album_id}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();

      $sql = "UPDATE song SET album_id=NULL WHERE song_id={$song_id}";
      $stmt = $this->db->con->prepare($sql);
      $stmt->execute();
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }
}
