<?php
require('controllers/MainController.php');
$songData = $song->getSingleSong($_GET['song_id']);

$isAdmin = true;

$judul = $songData['judul'] ?? null;
$penyanyi = $songData['penyanyi'] ?? null;
$tanggal = $songData['tanggal_terbit'] ?? null;
$genre = $songData['genre'] ?? null;
$durasi = $songData['duration'] ?? null;
$imagePath = $songData['image_path'] ?? null;
$songPath = $songData['audio_path'] ?? null;
$albumID = $songData['album_id'] ?? null;
$tombol_album = 'ini tombol album';

function echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath = 'assets/images/defaultImage.jpg', $songPath = '')
{
  $html = <<<"EOT"
  <div class="divider"> 
        <div class="image">
          <img src="{$imagePath}" height="200" width="200" />
        </div>
        <div class="info">
          <p class="title">$judul</p>
          <div class="rest-info">
            <p>$penyanyi</p>
            <p>$durasi</p>
            <p>$genre</p>
            <p>$tanggal</p>
          </div>
          <div class="audio-player">
            <audio controls>
              <source src="{$songPath}" type="audio/ogg" />
            </audio>
          </div>
        </div>
      </div>
  EOT;
  echo $html;
}

function echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath = 'assets/images/defaultImage.jpg', $songPath = '')
{
  $html = <<<"EOT"
  <div class="divider"> 
        <div class="image">
          <img src="{$imagePath}" height="200" width="200" />
        </div>
        <div class="info">
          <input type="text" name="judul" value="$judul">
          <div class="rest-info">
            <input type="text" name="penyanyi" value="$penyanyi">
            <p>$durasi</p>
            <input type="text" name="genre" value="$genre">
            <p></p>
            <input type="date" name="tanggal" value="$tanggal">
          </div>
          <p>File lagu:</p>
    <input type="file" name="songToUpload" id="songToUpload"><br>
    <p>File gambar:</p>
    <input type="file" name="imageToUpload" id="imageToUpload"><br>
    <p>Album:</p>
    <span>TBD s input selection for available album</span><br>
          <div class="audio-player">
            <audio controls>
              <source src="{$songPath}" type="audio/ogg" />
            </audio>
          </div>
        </div>
      </div>
  EOT;
  echo $html;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['save-edit-song'])) {
    // edit song logic
    $target_dir_song = "./assets/musics/";
    $target_dir_image = "./assets/images/";
    $songFile = $_FILES["songToUpload"]['name'] ?? null;
    $imageFile = $_FILES["imageToUpload"]['name'] ?? null;

    // change to existing path if song/image is not changed
    $target_file_song = $songFile ? $target_dir_song . 'song' . $_GET['song_id'] . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
    $target_file_image = $imageFile ? $target_dir_image . 'image' . $_GET['song_id'] . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION) : $songData['image_path'];

    $uploadOk = 1;

    // payload
    $judul = $_POST['judul'];
    $penyanyi = $_POST['penyanyi'];
    $tanggal = $_POST['tanggal'];
    $genre = $_POST['genre'];

    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      try {
        if ($songFile && $imageFile) {
          unlink($imagePath);
          unlink($songPath);
          move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
          move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

          $songPath = $_FILES["songToUpload"]["name"] ? $target_file_song : $songPath;
          $imagePath = $_FILES["imageToUpload"]['name'] ? $target_file_image : $imagePath;
        } elseif ($songFile) {
          unlink($songPath);
          move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);

          $songPath = $_FILES["songToUpload"]['name'] ? $target_file_song : $songPath;
        } elseif ($imageFile) {
          unlink($imagePath);
          move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

          $imagePath = $_FILES["imageToUpload"]['name'] ? $target_file_image : $imagePath;
        }

        $song->updateSong($judul, $penyanyi, $tanggal, $genre, 10, $target_file_song, $target_file_image, $_GET['song_id'], $albumID);
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
  <link rel="stylesheet" href="css/song.css">
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Song name</title>
</head>

<body>


  <form method="post" action="<?php echo 'song.php?song_id=' . $_GET['song_id'] ?>" enctype="multipart/form-data">
    <?php if (isset($_POST['edit-song'])) {
      echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath);
    } elseif (isset($_POST['save-edit-song'])) {
      echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath);
    } else {
      echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath);
    }
    ?>
    <?php if ($isAdmin && (isset($_POST['edit-song']) or isset($_POST['save-edit-song']))) {
      echo '<button type="submit" name="save-edit-song">save changes</button>';
      echo "<a href='./song.php?song_id={$_GET['song_id']}'>cancel</a>";
    } elseif ($isAdmin && !isset($_POST['edit-song'])) {
      echo '<button type="submit" name="edit-song">edit button</button>';
    }
    ?>
  </form>
</body>

</html>