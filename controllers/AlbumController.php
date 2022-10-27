<?php
  class AlbumController {
    public $db;

    public function __construct(DBController $db) {
      $this->db = $db;
    }

    public function getAlbums() {
      $conn = $this->db->getConnection();
      $stmt = $conn->prepare("SELECT * FROM album");
      $stmt->execute();
      $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($albums);
    }

    public function insertAlbum($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre) {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO album (judul, penyanyi, total_duration, image_path, tanggal_terbit, genre)
        VALUES (:judul, :penyanyi, :total_duration, :image_path, :tanggal_terbit, :genre)";
        $conn->prepare($sql)->execute(array(
          ':judul' => $judul,
          ':penyanyi' => $penyanyi,
          ':total_duration' => $total_duration,
          ':image_path' => $image_path,
          ':tanggal_terbit' => $tanggal_terbit,
          ':genre' => $genre
        ));
        echo "New record created successfully<br>";
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }

    public function getLastAlbumID() {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT album_id FROM album ORDER BY album_id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $album_id = $stmt->fetch(PDO::FETCH_ASSOC);
        return $album_id['album_id'];
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }

    public function getSingleAlbum($albumID) {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM album WHERE album_id = :album_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
          ':album_id' => $albumID
        ));
        $album = $stmt->fetch(PDO::FETCH_ASSOC);
        return $album;
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }

    public function getAlbumSong($albumID) {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM song WHERE album_id = :album_id ORDER BY judul ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
          ':album_id' => $albumID
        ));
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $songs;
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }

    public function getAllAlbum() {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM album ORDER BY judul ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $albums;
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }

    public function deleteAlbum($album_id) {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM album WHERE album_id = :album_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
          ':album_id' => $album_id
        ));
        echo "Record deleted successfully<br>";
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }

    public function updateAlbum($album_id, $judul, $genre, $tanggal_terbit, $image_path) {
      try {
        $conn = $this->db->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE album SET judul = :judul, genre = :genre, tanggal_terbit = :tanggal_terbit, image_path = :image_path WHERE album_id = :album_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
          ':album_id' => $album_id,
          ':judul' => $judul,
          ':genre' => $genre,
          ':tanggal_terbit' => $tanggal_terbit,
          ':image_path' => $image_path
        ));
      } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
    }
  }
?>