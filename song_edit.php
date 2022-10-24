<?php
require 'controllers/MainController.php';
$songData = $song->getSingleSong($_GET['song_id']);
print_r($songData);
$judul = $songData['judul'] ?? null;
$penyanyi = $songData['penyanyi'] ?? null;
$tanggal = $songData['tanggal_terbit'] ?? null;
$genre = $songData['genre'] ?? null;
$durasi = 0;
$imagePath = $songData['image_path'] ?? null;
$songPath = $songData['audio_path'] ?? null;
$tombol_album = 'ini tombol album';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (isset($_POST['submit'])){
    $songID = $_POST['song_id'];
    echo 'song id'.$songID;
    $target_dir_song = "./assets/musics/";
    $target_dir_image = "./assets/images/";
    $songFile = $_FILES["songToUpload"]["name"];
    $imageFile = $_FILES["imageToUpload"]['name'];
    
    // null diganti current path
    $target_file_song = $songFile ? $target_dir_song . 'song' . $newSongID . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
    $target_file_image = $imageFile ? $target_dir_image . 'image' . $newSongID . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
    $uploadOk = 1;
    // $musicFileType = strtolower(pathinfo($target_file_song, PATHINFO_EXTENSION));
    
    // payload
    $judul = $_POST['judul'];
    $penyanyi = $_POST['penyanyi'];
    $tanggal = $_POST['tanggal'];
    $genre = $_POST['genre'];
    
    if (isset($_POST["submit"])) {
      echo '$target_file_song' . $target_file_song;
      echo '<br>$target_file_image' . $target_file_image;
      // foreach ($song as $s) {
      //   echo "<div><p>" . $s['judul'] . "</p></div>";
      // } 
    }
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      echo '<br>move song' . move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
      echo '<br>move image' . move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);
    
      try {
        if ($songFile && $imageFile) {
          unlink($target_file_song);
          unlink($target_file_image);
          move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);
    
          echo "--------------------------------------";
          echo "<br>";
          echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
          echo "<br>";
          echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
        } elseif ($songFile) {
          unlink($target_file_song);
          move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
          echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
        } elseif ($imageFile) {
          unlink($target_file_image);
          move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);
          echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
        } else{
          echo 'song updated';
        }
        $song->updateSong($judul, $penyanyi, $tanggal, $genre, '10', $target_file_song, $target_file_image, $songID);
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
    
  }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php if ($songData) { ?>
    <form method="post" enctype="multipart/form-data">
    <!-- auto count duration -->
    <input type="hidden" name="song_id" value="<?php echo $_GET['song_id'] ?>">
    Judul:
    <input type="text" name='judul' value=<?php echo $judul ?>><br>
    Penyanyi:
    <input type="text" name='penyanyi' value=<?php echo $penyanyi ?>><br>
    Tanggal:
    <input type="date" name='tanggal' value=<?php echo $tanggal ?>><br>
    Genre:
    <input type="text" name='genre' value=<?php echo $genre ?>><br>
    File lagu:
    <input type="file" name="songToUpload" id="songToUpload"><br>
    File gambar:
    <input type="file" name="imageToUpload" id="imageToUpload"><br>
    Album:
    <span>TBD s input selection for available album</span><br>
    <button type="submit" value="Upload Song" name="submit">Save Changes</button>
  </form>
  <?php } else { ?>
    <p>song not found</p>
  <?php } ?>
  
</body>

</html>