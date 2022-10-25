<?php
require('controllers/MainController.php');
echo '<br>' . $_GET['song_id'] . '<br>';
$songData = $song->getSingleSong($_GET['song_id']);
print_r($songData);
echo '<br>' . $songData['audio_path'];
echo '<br>' . '-------------debug------------' . '<br>';

$isAdmin = true;

$judul = $songData['judul'] ?? null;
$penyanyi = $songData['penyanyi'] ?? null;
$tanggal = $songData['tanggal_terbit'] ?? null;
$genre = $songData['genre'] ?? null;
$durasi = 0;
$imagePath = $songData['image_path'] ?? null;
$songPath = $songData['audio_path'] ?? null;
$albumID = $songData['album_id'] ?? null;
$tombol_album = 'ini tombol album';

function echoSongDetail($judul = 'ini judul', $penyanyi = 'ini penyanyi', $tanggal = 'ini tanggal', $genre = 'ini genre', $durasi = 'ini durasi', $imagePath = 'assets/images/defaultImage.jpg', $songPath = '', $tombol_album = 'ini tombol album')
{
  $html = <<<"EOT"
  <div class="divider"> 
        <div class="image">
          <img src="{$imagePath}" height="200" width="200" />
        </div>
        <div class="info">
          <p class="title">$judul</p>
          <div class="singer-duration">
            <p>$penyanyi</p>
            <p>&nbsp;&nbsp;&nbsp;</p>
            <p>$durasi</p>
          </div>
          <div class="genre-date">
            <p>$genre</p>
            <p>&nbsp;&nbsp;&nbsp;</p>
            <p>$tanggal</p>
          </div>
          <div class="audio-player">
            <audio controls>
              <source src="{$songPath}" type="audio/ogg" />
            </audio>
          </div>
        </div>
        <p>$tombol_album</p>
      </div>
  EOT;
  echo $html;
}

function echoSongEdit($judul = 'ini judul', $penyanyi = 'ini penyanyi', $tanggal = 'ini tanggal', $genre = 'ini genre', $durasi = 'ini durasi', $imagePath = 'assets/images/defaultImage.jpg', $songPath = '', $tombol_album = 'ini tombol album')
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
    echo 'MASUK SAVE EDIT SONG';
    $songID = $_GET['song_id'];
    echo 'song id' . $songID;
    $target_dir_song = "./assets/musics/";
    $target_dir_image = "./assets/images/";
    $songFile = $_FILES["songToUpload"]['name'] ?? null;
    $imageFile = $_FILES["imageToUpload"]['name'] ?? null;
    echo '<br> song file name' . $songFile;
    echo '<br> image file name' . $imageFile;
    // null diganti current path
    $target_file_song = $songFile ? $target_dir_song . 'song' . $songID . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
    $target_file_image = $imageFile ? $target_dir_image . 'image' . $songID . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION) : $songData['image_path'];
    
    $uploadOk = 1;
    // $musicFileType = strtolower(pathinfo($target_file_song, PATHINFO_EXTENSION));

    // payload
    $judul = $_POST['judul'];
    $penyanyi = $_POST['penyanyi'];
    $tanggal = $_POST['tanggal'];
    $genre = $_POST['genre'];


    if (isset($_POST["save-edit-song"])) {
      echo '<br>$target_file_song' . $target_file_song;
      echo '<br>$target_file_image' . $target_file_image;
      // foreach ($song as $s) {
      //   echo "<div><p>" . $s['judul'] . "</p></div>";
      // } 
    }
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      // echo '<br>move song' . move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
      // echo '<br>move image' . move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

      try {
        if ($songFile && $imageFile) {
          echo '<br> song and image file <br>';
          unlink($imagePath);
          unlink($songPath);
          move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
          move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

          echo "--------------------------------------";
          echo "<br>";
          echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
          echo "<br>";
          echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
          $songPath = $_FILES["songToUpload"]["name"] ? $target_file_song : $songPath;
          $imagePath = $_FILES["imageToUpload"]['name'] ? $target_file_image : $imagePath;
        } elseif ($songFile) {
          echo '<br> song file <br>';
          unlink($songPath);
          move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
          echo "The file " . htmlspecialchars(basename($_FILES["songToUpload"]["name"])) . " has been uploaded.";
          $songPath = $_FILES["songToUpload"]['name'] ? $target_file_song : $songPath;
        } elseif ($imageFile) {
          echo '<br> image file <br>';
          unlink($imagePath);
          move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);
          echo "The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.";
          $imagePath = $_FILES["imageToUpload"]['name'] ? $target_file_image : $imagePath;
        } else {
          echo '<br>song updated without file changes';
        }

        $song->updateSong($judul, $penyanyi, $tanggal, $genre, 10, $target_file_song, $target_file_image, $songID, $albumID);
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
      echo '<h1>$_POST[edit-song]</h1>';
      echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath, $tombol_album);
    } elseif (isset($_POST['save-edit-song'])) {
      echo '<h1>$_POST[save-edit-song]</h1>';
      echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath, $tombol_album);
    } else {
      echo '<h1>normal</h1>';
      echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath, $tombol_album);
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